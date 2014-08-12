<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use PredictionIO\PredictionIOClient;

class FixDB extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lara:fixdb';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fix DB.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
        /*$objects = Group::all();

        foreach ($objects as $object)
        {
            $count = GroupSubscriber::where('group_id', $object->getKey())->count();
            $object->subscribers = intval($count);
            $object->save();
        }*/

        /*$objects = GroupModerator::all();

        foreach ($objects as $object)
        {
            if (!is_object($object->group))
                $object->delete();
        }*/

        /*$objects = Content::all();

        foreach ($objects as $object)
        {
            $object->thumbnail = intval($count);
            $object->save();
        }*/

        /*foreach (CommentReplyVote::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (CommentReply::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (CommentVote::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (Comment::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (ContentRelatedVote::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (ContentRelated::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (ContentVote::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (Content::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (Conversation::all() as $ob) {
            $newusers = array();

            foreach ($ob->users as $user) {
                $newusers[] = User::find($user)->name;
            }

            $ob->users = $newusers;

            $ob->save();
        }

        foreach (ConversationMessage::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (EntryReply::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (EntryReplyVote::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (EntryVote::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (Entry::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (Group::all() as $ob) {
            $ob->creator_id = $ob->creator->name;
            $ob->save();
        }

        foreach (GroupBanned::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (GroupModerator::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (GroupSubscriber::all() as $ob) {
            $ob->user_id = $ob->user->name;
            $ob->save();
        }

        foreach (Notification::all() as $ob) {
            $ob->source_user_id = $ob->sourceUser->name;
            $ob->user_id = $ob->user->name;

            $ob->save();
        }*/

        /*
        db.users.find().forEach(function(doc){
            var id=doc._id;
            doc._id=doc.name;
            db.users.remove({_id:id});
            db.users.insert(doc);
        })

        */

       /*foreach (ContentRelatedVote::all() as $ob) {
            try {
                $ob->related_id = $ob->related->id;
                $ob->save();
            } catch(Exception $e) {
            }
        }*/

        /*
        db.content_related.find().forEach(function(doc){
            var id=doc._id;
            doc._id=doc.id;
            db.content_related.remove({_id:id});
            db.content_related.insert(doc);
        })

        db.contents.find({ uv: { $gt: 7 } }).forEach(function(doc){
            doc.frontpage_at = doc.created_at;
            db.contents.save(doc);
        })
        */

       /*
        * Prediction.io - data importing
        *

        DB::connection()->disableQueryLog();

        $client = PredictionIOClient::factory(array(
            "appkey" => "zGx5TBpYIxgrsHNd8ZJvjCa6buYUc10SMTwNKVthJSaGl0gacOyNXKDBxmhTQmjT",
            'apiurl' => 'http://host:8000',
        ));

        foreach(User::all() as $user) {
            echo "Add user ". $user->_id . "\n";
            $command = $client->getCommand('create_user', array('pio_uid' => $user->_id));
            $response = $client->execute($command);
        }

        foreach (Group::all() as $group) {
            echo "Add group ". $group->_id . "\n";
            $command = $client->getCommand('create_item', array('pio_iid' => $group->_id, 'pio_itypes' => 1));
            $response = $client->execute($command);
        }

        foreach (GroupSubscriber::all() as $subscribe) {
            echo "User ". $subscribe->user_id . " subscribes group ". $subscribe->group_id ."\n";
            $client->identify($subscribe->user_id);
            $client->execute($client->getCommand('record_action_on_item', array('pio_action' => 'like', 'pio_iid' => $subscribe->group_id)));
        }

        foreach (GroupBlock::all() as $block) {
            echo "User ". $block->user_id . " blocks group ". $block->group_id ."\n";
            $client->identify($block->user_id);
            $client->execute($client->getCommand('record_action_on_item', array('pio_action' => 'dislike', 'pio_iid' => $block->group_id)));
        }

        foreach (UserData::all() as $data)
        {
            $client->identify($data->_id);
            $rec = $client->execute($client->getCommand('itemrec_get_top_n', array('pio_engine' => 'engine1', 'pio_n' => 10)));

            $data->recommended_groups = $rec['pio_iids'];
            $data->save();
        }

        */

        // foreach (User::all() as $user) { $user->email = $user->email; $user->save(); }

        /*foreach (Content::where('created_at', '>', new MongoDate(time() - 14 * 86400))->get() as $content) {
            $count = 0;
            $comments = $content->getComments();

            foreach ($comments as $comment)
            {
                $count++;

                foreach ($comment->replies as $reply) { $count++; }
            }

            $content->comments = $count;
            $content->save();
        }*/

        /*
         *
        $pslManager = new Pdp\PublicSuffixListManager();
        $parser = new Pdp\Parser($pslManager->getList());

        foreach (Content::all() as $c) { if(!$c->url) continue; $url = $parser->parseUrl($c->url); $c->domain = $url->host->registerableDomain; $c->save(); }

        */

        DB::connection()->disableQueryLog();

        foreach (Comment::all() as $comment)
        {
            $content = $comment->content;

            if (!$content) continue;

            $comment->group()->associate($content->group);
            $comment->save();
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}