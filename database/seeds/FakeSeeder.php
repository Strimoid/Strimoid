<?php

use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Group;
use Strimoid\Models\GroupSubscriber;
use Strimoid\Models\User;

class FakeSeeder extends BaseSeeder
{

    private $groups = [];
    private $users = [];

    const SEED = 12345;

    public function run()
    {
        $this->cleanDatabase();

        // Make sure we will get same data every time
        $this->faker->seed(self::SEED);

        for ($i = 0; $i < 50; $i++) {
            $this->createFakeUser();
        }
    }

    protected function cleanDatabase()
    {
        $tables = ['contents', 'entries', 'groups',
            'group_subscribers', 'users'];

        foreach ($tables as $table)
        {
            DB::table($table)->truncate();
        }
    }

    protected function createFakeUser()
    {
        $user = User::create([
            'created_at' => $this->faker->dateTimeThisDecade,
            'name' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'is_activated' => true,
        ]);

        $this->users[] = $user;

        $groupsNumber = $this->faker->numberBetween(0, 10);

        for ($i = 0; $i < $groupsNumber; $i++) {
            $this->createFakeGroup($user);
        }
    }

    protected function createFakeGroup(User $creator)
    {
        $group = Group::create([
            'created_at'    => $this->faker->dateTimeThisDecade,
            'creator_id'    => $creator->getKey(),
            'name'          => $this->faker->city,
            'description'   => implode(' ', $this->faker->sentences(2)),
            'sidebar'       => $this->faker->paragraph,
            'tags'          => $this->faker->words(5),
            'urlname'       => $this->faker->domainWord,
        ]);

        $contentsNumber = $this->faker->numberBetween(0, 10);

        for ($i = 0; $i < $contentsNumber; $i++) {
            $this->createFakeContent($group);
            $this->createFakeEntry($group);
        }

        $subscribersNumber = $this->faker->numberBetween(0, count($this->users));

        for ($i = 0; $i < $subscribersNumber; $i++) {
            $subscriber = $this->users[$i];
            $this->createFakeSubscriber($group, $subscriber);
        }
    }

    protected function createFakeContent(Group $group)
    {
        $content = Content::create([
            with (new Content)->getKeyName() => $this->getRandomId(),
            'created_at'    => $this->faker->dateTimeThisDecade,
            'group_id'      => $group->getKey(),
            'title'         => $this->faker->sentence(10),
            'description'   => $this->faker->text(200),
            'user_id'       => $this->getRandomUser()->getKey(),
            'url'           => $this->faker->url,
            'nsfw'          => $this->faker->boolean,
            'eng'           => $this->faker->boolean,
        ]);

        $commentsNumber = $this->faker->numberBetween(0, 3);

        for ($i = 0; $i < $commentsNumber; $i++) {
            $this->createFakeComment($content);
        }
    }

    protected function createFakeComment(Content $content)
    {
        $comment = Comment::create([
            with(new Comment)->getKeyName() => $this->getRandomId(),
            'content_id'    => $content->getKey(),
            'created_at'    => $this->faker->dateTimeThisDecade,
            'text'          => $this->faker->text(512),
            'user_id'       => $this->getRandomUser()->getKey(),
        ]);

        $repliesNumber = $this->faker->numberBetween(0, 3);

        for ($i = 0; $i < $repliesNumber; $i++) {
            $this->createFakeCommentReply($comment);
        }
    }

    protected function createFakeCommentReply(Comment $comment)
    {
        $comment->replies()->create([
            with(new CommentReply)->getKeyName() => $this->getRandomId(),
            'created_at'    => $this->faker->dateTimeThisDecade,
            'text'          => $this->faker->text(512),
            'user_id'       => $this->getRandomUser()->getKey(),
        ]);
    }

    protected function createFakeEntry(Group $group)
    {
        $entry = Entry::create([
            with(new Entry)->getKeyName() => $this->getRandomId(),
            'created_at'    => $this->faker->dateTimeThisDecade,
            'group_id'      => $group->getKey(),
            'text'          => $this->faker->text(512),
            'user_id'       => $this->getRandomUser()->getKey(),
        ]);

        $repliesNumber = $this->faker->numberBetween(0, 3);

        for ($i = 0; $i < $repliesNumber; $i++) {
            $this->createFakeEntryReply($entry);
        }
    }

    protected function createFakeEntryReply(Entry $entry)
    {
        $entry->replies()->create([
            with(new EntryReply)->getKeyName() => $this->getRandomId(),
            'created_at'    => $this->faker->dateTimeThisDecade,
            'text'          => $this->faker->text(512),
            'user_id'       => $this->getRandomUser()->getKey(),
        ]);
    }

    protected function createFakeSubscriber(Group $group, User $user)
    {
        GroupSubscriber::create([
            'group_id'      => $group->getKey(),
            'user_id'       => $user->getKey(),
        ]);
    }

    protected function getRandomId()
    {
        $md5 = $this->faker->unique()->md5;
        return substr($md5, 0, 6);
    }

    protected function getRandomUser()
    {
        $total = count($this->users);
        $key = $this->faker->numberBetween(0, $total - 1);

        return $this->users[$key];
    }

}
