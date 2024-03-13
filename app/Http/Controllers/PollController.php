<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Strimoid\Models\Content;

class PollController extends BaseController
{
    public function __construct(private readonly \Illuminate\Auth\AuthManager $authManager, private readonly \Illuminate\Routing\Redirector $redirector, private readonly \Illuminate\Validation\Factory $validationFactory)
    {
    }
    public function addVote(Request $request, Content $content)
    {
        $poll = $content->poll;

        // No double voting, sorry
        $hasVoted = in_array($this->authManager->id(), array_column($poll['votes'], 'user_id'));

        if ($hasVoted) {
            return $this->redirector->route('content_comments', $content->getKey())
                ->with('danger_msg', 'Oddałeś już głos w tej ankiecie.');
        }

        // Check if poll isn't closed already
        if (isset($poll['ends_at']) && Carbon::now()->gte($poll['ends_at'])) {
            return $this->redirector->route('content_comments', $content->getKey())
                ->with('danger_msg', 'Ankieta została już zakończona.');
        }

        // Create validation rules for all questions
        $rules = [];

        foreach ($poll['questions'] as $questionId => $question) {
            $rules[$questionId] = ['array', 'min:' . $question['min_selections'], 'max:' . $question['max_selections']];

            if ($question['min_selections']) {
                $rules[$questionId][] = 'required';
            }
        }

        // Now validate replies
        $validator = $this->validationFactory->make($request->all(), $rules, [
            'required' => 'Odpowiedź na to pytanie jest wymagana',
            'min' => 'Zaznaczyłeś zbyt małą liczbę odpowiedzi',
            'max' => 'Zaznaczyłeś zbyt dużą liczbę odpowiedzi',
        ]);

        if ($validator->fails()) {
            return $this->redirector->route('content_comments', $content->getKey())
                ->withInput()
                ->withErrors($validator);
        }

        // And add vote object to poll
        $replies = [];

        foreach ($poll['questions'] as $questionId => $question) {
            $optionIds = (array) $request->get($questionId);

            foreach ($optionIds as $optionId) {
                if (!in_array($optionId, array_column($question['options'], '_id'))) {
                    return $this->redirector->route('content_comments', $content->getKey())
                        ->withInput()
                        ->with('danger_msg', 'Wygląda na to, że jedna z odpowiedzi została usunięta. Spróbuj jeszcze raz.');
                }
            }

            $replies[$questionId] = $optionIds;
        }

        foreach ($replies as $questionId => $optionIds) {
            if (!$optionIds) {
                continue;
            }

            foreach ($optionIds as $optionId) {
                Content::where('_id', $content->getKey())
                    ->where('poll.questions.' . $questionId . '.options', 'elemmatch', ['_id' => $optionId])
                    ->increment('poll.questions.' . $questionId . '.options.$.votes', 1);
            }
        }

        $vote = ['created_at' => Carbon::now(), 'user_id' => $this->authManager->id(), 'replies' => $replies];

        $content->push('poll.votes', $vote);

        return $this->redirector->route('content_comments', $content->getKey())
            ->with('success_msg', 'Twój głos został dodany.');
    }
}
