<?php namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Strimoid\Models\Conversation;
use Strimoid\Models\ConversationMessage;
use Strimoid\Models\Notification;
use Strimoid\Models\User;

class ConversationController extends BaseController
{
    public function showConversation($id = null)
    {
        $conversations = $this->getConversations();

        $data['messages'] = [];

        if ($id) {
            $conversation = Conversation::withUser(Auth::id())->findOrFail($id);
        } elseif ($conversations->first()) {
            $conversation = $conversations->first();
        }

        $data['conversations'] = $conversations;

        if (isset($conversation)) {
            $data['conversation'] = $conversation;
            $data['messages'] = $conversation->messages()
                ->orderBy('created_at', 'desc')
                ->paginate(50);
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

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'text'    => $request->input('text'),
        ]);

        $this->sendNotifications([$target->getKey()], function ($notification) use ($message, $conversation) {
            $notification->type = 'conversation';
            $notification->setTitle($message->text);
            $notification->conversation()->associate($conversation);
            $notification->save(); // todo
        });

        return Redirect::to('/conversations');
    }

    public function sendMessage(Request $request)
    {
        $conversation = Conversation::where('users', Auth::id())
            ->where('id', Input::get('id'))->firstOrFail();

        $target = $conversation->getUser();

        if ($target->isBlockingUser(Auth::user())) {
            return Redirect::route('conversation', $conversation->getKey())
                ->withInput()
                ->with('danger_msg', 'Zostałeś zablokowany przez wybranego użytkownika.');
        }

        $this->validate($request, ['text' => 'required|max:10000']);

        Notification::where('type', 'conversation')
            ->where('conversation_id', $conversation->getKey())
            ->where('user_id', $target->getKey())->delete();

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'text'    => $request->input('text'),
        ]);

        $this->sendNotifications([$target->getKey()], function ($notification) use ($message, $conversation) {
            $notification->type = 'conversation';
            $notification->setTitle($message->text);
            $notification->conversation()->associate($conversation);
            $notification->save(); // todo
        });

        return Redirect::route('conversation', $conversation->getKey());
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
        $conversations = Conversation::with('lastMessage')
            ->with('lastMessage.user')
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
