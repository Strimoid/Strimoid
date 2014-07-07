<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ConvertVotes extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:convertvotes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert votes.';

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

        /*foreach (CommentVote::all() as $ob) {
            $ob->comment->mpush('votes', ['user_id' => $ob->user_id, 'created_at' => new MongoDate($ob->created_at->timestamp), 'up' => $ob->up]);
            $ob->delete();
        }

        $this->info('Comment votes converted');

        foreach (CommentReplyVote::all() as $ob) {
            $ob->reply->mpush('votes', ['user_id' => $ob->user_id, 'created_at' => new MongoDate($ob->created_at->timestamp), 'up' => $ob->up]);
            $ob->delete();
        }

        $this->info('Comment reply votes converted');

        foreach (ContentRelatedVote::all() as $ob) {
            $ob->related->mpush('votes', ['user_id' => $ob->user_id, 'created_at' => new MongoDate($ob->created_at->timestamp), 'up' => $ob->up]);
            $ob->delete();
        }

        $this->info('Content related votes converted');

        foreach (ContentVote::all() as $ob) {
            if (!$ob->content)
                continue;

            $ob->content->mpush('votes', ['user_id' => $ob->user_id, 'created_at' => new MongoDate($ob->created_at->timestamp), 'up' => $ob->up]);
            $ob->delete();
        }

        $this->info('Content votes converted');

        foreach (EntryVote::all() as $ob) {
            $ob->entry->mpush('votes', ['user_id' => $ob->user_id, 'created_at' => new MongoDate($ob->created_at->timestamp), 'up' => $ob->up]);
            $ob->delete();
        }

        $this->info('Entry votes converted');

        foreach (EntryReplyVote::all() as $ob) {
            $ob->reply->mpush('votes', ['user_id' => $ob->user_id, 'created_at' => new MongoDate($ob->created_at->timestamp), 'up' => $ob->up]);
            $ob->delete();
        }

        $this->info('Entry reply votes converted');*/

        // db.contents.update({}, { $rename: { "comments": "comments_count" } }, { multi: true })

        DB::connection()->disableQueryLog();

        /*$x = 1;

        foreach (CommentReply::orderBy('created_at', 'asc')->get() as $ob) {
            $parent = Comment::find($ob->comment_id);

            if (!$parent)
                continue;

            unset($ob->comment_id);

            $ob->exists = false;

            $parent->replies()->save($ob);

            $ob->exists = true;

            //$ob->delete();

            if (!($x % 100))
                $this->info($x .' comment replies converted');

            $x++;
        }

        $this->info('All comment replies converted');*/

        /*$x = 1;

        foreach (Entry::orderBy('created_at', 'asc')->get() as $ob) {

            foreach ($ob->replies as $reply) {
                $old = OldReply::find($reply->_id);

                if (!$old)
                    continue;

                $reply->created_at = $old->created_at;
                $reply->updated_at = $old->updated_at;

                $reply->save();
            }
            if (!($x % 100))
                $this->info($x .' comment replies converted');

            $x++;
        }

        $this->info('All comment replies converted');*/

        $x = 1;

        foreach (Notification::orderBy('created_at', 'asc')->where('type', 'comment_reply')->get() as $ob) {
            $reply = CommentReply::find($ob->comment_reply_id);

            if (!$ob->content_id && $reply)
            {
                $ob->content_id = $reply->comment->content_id;
                $ob->save();
            }

            if (!($x % 100))
                $this->info($x .' notifications converted');

            $x++;
        }

        $this->info('All notifications converted');

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