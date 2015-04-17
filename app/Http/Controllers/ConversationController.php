<?php namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Strimoid\Models\Conversation;
use Strimoid\Models\ConversationMessage;
use Strimoid\Models\User;

class ConversationController extends BaseController
{
    public function showConversation($conversation = null)
    {
        $conversations = $this->getConversations();

        $data['messages'] = [];

        if ($conversation && $conversation->users->where('id', Auth::id())->count()) {
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

    public function showCreateForm($username = null)
    {
        $conversations = $this->getConversations();

        return view('conversations.create', compact('conversations', 'username'));
    }

    public function createConversation(Request $request)
    {
        $target = User::name(Input::get('username'))->firstOrFail();

        if ($target->getKey() == Auth::id()) {
            return Redirect::action('ConversationController@showCreateForm')
                ->withInput()
                ->with('danger_msg', 'Ekhm... wysyłanie wiadomości do samego siebie chyba nie ma sensu ;)');
        }

        if ($target->isBlockingUser(Auth::user())) {
            return Redirect::action('ConversationController@showCreateForm')
                ->withInput()
                ->with('danger_msg', 'Zostałeś zablokowany przez wybranego użytkownika.');
        }

        $this->validate($request, ['text' => 'required|max:10000']);

        $conversation = Conversation::withUser(Auth::id())
            ->withUser($target->getKey())
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([]);
            $conversation->users()->attach([
                Auth::id(), $target->getKey()
            ]);
        } else {
            $conversation->notifications()
                ->where('user_id', $target->getKey())
                ->delete();
        }

        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'text'    => $request->input('text'),
        ]);

        return Redirect::to('/conversations');
    }

    public function sendMessage(Request $request)
    {
        $ids = \Hashids::decode($request->input('id'));
        $id = current($ids);

        $conversation = Conversation::withUser(Auth::id())
            ->where('id', $id)->firstOrFail();

        $target = $conversation->target();

        if ($target->isBlockingUser(Auth::user())) {
            return Redirect::route('conversation', $conversation->getKey())
                ->withInput()
                ->with('danger_msg', 'Zostałeś zablokowany przez wybranego użytkownika.');
        }

        $this->validate($request, ['text' => 'required|max:10000']);

        $conversation->notifications()->where('user_id', $target->getKey())->delete();

        $conversation->messages()->create([
            'user_id' => Auth::id(),
            'text'    => $request->input('text'),
        ]);

        return Redirect::route('conversation', $conversation);
    }

    private function getConversations()
    {
        return Conversation::with('lastMessage')
            ->withUser(Auth::id())
            ->get()
            ->sortBy(function ($conversation) {
                return $conversation->lastMessage->created_at;
            })->reverse();
    }

    public function getIndex()
    {
        $conversations = Conversation::with('lastMessage', 'lastMessage.user')
            ->withUser(Auth::id())
            ->get()
            ->sortBy(function ($conversation) {
                return $conversation->lastMessage->created_at;
            })->reverse();

        return $conversations;
    }

    public function getMessages()
    {
        $ids = Conversation::withUser(Auth::id())->lists('id');

        $messages = ConversationMessage::with('conversation')->with('user')
            ->whereIn('conversation_id', $ids)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return $messages;
    }
}
