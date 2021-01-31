<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Strimoid\Models\Conversation;
use Strimoid\Models\User;
use Vinkla\Hashids\Facades\Hashids;

class ConversationController extends BaseController
{
    public function showConversation($conversation = null)
    {
        $conversations = $this->getConversations();

        $data['messages'] = [];

        if ($conversation && $conversation->users->where('id', auth()->id())->count()) {
        } elseif ($conversations->first()) {
            $conversation = $conversations->first();
        }

        $data['conversations'] = $conversations;

        if (isset($conversation)) {
            $data['conversation'] = $conversation;
            $data['messages'] = $conversation->messages()->paginate(50);
        }

        return view('conversations.display', $data);
    }

    public function showCreateForm($user = null)
    {
        $conversations = $this->getConversations();
        $username = $user ? $user->name : '';

        return view('conversations.create', compact('conversations', 'username'));
    }

    public function createConversation(Request $request)
    {
        $target = User::name(request('username'))->firstOrFail();

        if ($target->getKey() == auth()->id()) {
            return redirect()->action('ConversationController@showCreateForm')
                ->withInput()
                ->with('danger_msg', 'Ekhm... wysyłanie wiadomości do samego siebie chyba nie ma sensu ;)');
        }

        if ($target->isBlockingUser(user())) {
            return redirect()->action('ConversationController@showCreateForm')
                ->withInput()
                ->with('danger_msg', 'Zostałeś zablokowany przez wybranego użytkownika.');
        }

        $this->validate($request, ['text' => 'required|max:10000']);

        $conversation = Conversation::withUser(auth()->id())
            ->withUser($target->getKey())
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([]);
            $conversation->users()->attach([
                auth()->id(), $target->getKey(),
            ]);
        } else {
            $conversation->notifications()
                ->where('user_id', $target->getKey())
                ->delete();
        }

        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'text' => $request->input('text'),
        ]);

        return redirect()->to('/conversations');
    }

    public function sendMessage(Request $request)
    {
        $ids = Hashids::decode($request->input('id'));
        $id = current($ids);

        $conversation = Conversation::withUser(Auth::id())
            ->where('id', $id)->firstOrFail();

        $target = $conversation->target();

        if ($target->isBlockingUser(Auth::user())) {
            return redirect()->route('conversation', $conversation->getKey())
                ->withInput()
                ->with('danger_msg', 'Zostałeś zablokowany przez wybranego użytkownika.');
        }

        $this->validate($request, ['text' => 'required|max:10000']);

        $conversation->notifications()->where('user_id', $target->getKey())->delete();

        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'text' => $request->input('text'),
        ]);

        return redirect()->route('conversation', $conversation);
    }

    private function getConversations()
    {
        return Conversation::with('lastMessage')
            ->withUser(Auth::id())
            ->get()
            ->sortBy(fn ($conversation) => $conversation->lastMessage->created_at)->reverse();
    }
}
