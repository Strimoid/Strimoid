<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Strimoid\Models\Conversation;
use Strimoid\Models\User;
use Vinkla\Hashids\Facades\Hashids;

class ConversationController extends BaseController
{
    public function __construct(private readonly \Illuminate\Contracts\Auth\Guard $guard, private readonly \Illuminate\Contracts\View\Factory $viewFactory, private readonly \Illuminate\Routing\Redirector $redirector, private readonly \Illuminate\Auth\AuthManager $authManager)
    {
    }
    public function showConversation($conversation = null)
    {
        $conversations = $this->getConversations();

        $data['messages'] = [];

        if ($conversation && $conversation->users->where('id', $this->guard->id())->count()) {
        } elseif ($conversations->first()) {
            $conversation = $conversations->first();
        }

        $data['conversations'] = $conversations;

        if (isset($conversation)) {
            $data['conversation'] = $conversation;
            $data['messages'] = $conversation->messages()->paginate(50);
        }

        return $this->viewFactory->make('conversations.display', $data);
    }

    public function showCreateForm($user = null)
    {
        $conversations = $this->getConversations();
        $username = $user->name ?? '';

        return $this->viewFactory->make('conversations.create', compact('conversations', 'username'));
    }

    public function createConversation(Request $request): RedirectResponse
    {
        $target = User::name($request->input('username'))->firstOrFail();

        if ($target->getKey() === $this->guard->id()) {
            return $this->redirector->action('ConversationController@showCreateForm')
                ->withInput()
                ->with('danger_msg', 'Ekhm... wysyłanie wiadomości do samego siebie chyba nie ma sensu ;)');
        }

        if ($target->isBlockingUser(user())) {
            return $this->redirector->action('ConversationController@showCreateForm')
                ->withInput()
                ->with('danger_msg', 'Zostałeś zablokowany przez wybranego użytkownika.');
        }

        $this->validate($request, ['text' => 'required|max:10000']);

        $conversation = Conversation::withUser($this->guard->id())
            ->withUser($target->getKey())
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([]);
            $conversation->users()->attach([
                $this->guard->id(), $target->getKey(),
            ]);
        } else {
            $conversation->notifications()
                ->where('user_id', $target->getKey())
                ->delete();
        }

        $conversation->messages()->create([
            'user_id' => $this->authManager->id(),
            'text' => $request->input('text'),
        ]);

        return $this->redirector->to('/conversations');
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        $ids = Hashids::decode($request->input('id'));
        $id = current($ids);

        $conversation = Conversation::withUser($this->authManager->id())
            ->where('id', $id)->firstOrFail();

        $target = $conversation->target();

        if ($target->isBlockingUser($this->authManager->user())) {
            return $this->redirector->route('conversation', $conversation->getKey())
                ->withInput()
                ->with('danger_msg', 'Zostałeś zablokowany przez wybranego użytkownika.');
        }

        $this->validate($request, ['text' => 'required|max:10000']);

        $conversation->notifications()->where('user_id', $target->getKey())->delete();

        $conversation->messages()->create([
            'user_id' => $this->authManager->id(),
            'text' => $request->input('text'),
        ]);

        return $this->redirector->route('conversation', $conversation);
    }

    private function getConversations()
    {
        return Conversation::with('lastMessage')
            ->withUser($this->authManager->id())
            ->get()
            ->sortBy(fn ($conversation) => $conversation->lastMessage->created_at)->reverse();
    }
}
