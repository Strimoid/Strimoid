<?php

use Faker\Generator;
use Strimoid\Models\Comment;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Group;
use Strimoid\Models\User;

class FakeSeeder
{
    private const SEED = 12345;

    private array $users = [];
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }

    public function run()
    {
        // Make sure we will get same data every time
        $this->faker->seed(self::SEED);

        for ($i = 0; $i < 10; $i++) {
            $this->createFakeUser();
        }
    }

    protected function createFakeUser()
    {
        $user = User::create([
            'created_at'   => $this->faker->dateTimeThisDecade,
            'name'         => $this->faker->userName,
            'email'        => $this->faker->email,
            'password'     => 'qwe123',
            'is_activated' => true,
            'last_ip'      => '127.0.0.1',
        ]);

        $this->users[] = $user;

        $groupsNumber = $this->faker->numberBetween(0, 2);

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
            //'tags'          => $this->faker->words(5),
            'urlname'       => $this->faker->domainWord,
        ]);

        $contentsNumber = $this->faker->numberBetween(0, 5);

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
            'created_at'                      => $this->faker->dateTimeThisDecade,
            'group_id'                        => $group->getKey(),
            'title'                           => $this->faker->sentence(10),
            'description'                     => $this->faker->text(200),
            'user_id'                         => $this->getRandomUser()->getKey(),
            'url'                             => $this->faker->url,
            'nsfw'                            => $this->faker->boolean,
            'eng'                             => $this->faker->boolean,
        ]);

        $commentsNumber = $this->faker->numberBetween(0, 3);

        for ($i = 0; $i < $commentsNumber; $i++) {
            $this->createFakeComment($content);
        }
    }

    protected function createFakeComment(Content $content)
    {
        $comment = Comment::create([
            'content_id'                      => $content->getKey(),
            'created_at'                      => $this->faker->dateTimeThisDecade,
            'text'                            => $this->faker->text(512),
            'user_id'                         => $this->getRandomUser()->getKey(),
        ]);

        $repliesNumber = $this->faker->numberBetween(0, 3);

        for ($i = 0; $i < $repliesNumber; $i++) {
            $this->createFakeCommentReply($comment);
        }
    }

    protected function createFakeCommentReply(Comment $comment)
    {
        $comment->replies()->create([
            'created_at'                           => $this->faker->dateTimeThisDecade,
            'text'                                 => $this->faker->text(512),
            'user_id'                              => $this->getRandomUser()->getKey(),
        ]);
    }

    protected function createFakeEntry(Group $group)
    {
        $entry = Entry::create([
            'created_at'                    => $this->faker->dateTimeThisDecade,
            'group_id'                      => $group->getKey(),
            'text'                          => $this->faker->text(512),
            'user_id'                       => $this->getRandomUser()->getKey(),
        ]);

        $repliesNumber = $this->faker->numberBetween(0, 3);

        for ($i = 0; $i < $repliesNumber; $i++) {
            $this->createFakeEntryReply($entry);
        }
    }

    protected function createFakeEntryReply(Entry $entry)
    {
        $entry->replies()->create([
            'created_at'                         => $this->faker->dateTimeThisDecade,
            'text'                               => $this->faker->text(512),
            'user_id'                            => $this->getRandomUser()->getKey(),
        ]);
    }

    protected function createFakeSubscriber(Group $group, User $user)
    {
        $user->subscribedGroups()->attach($group);
    }

    protected function getRandomUser()
    {
        $total = count($this->users);
        $key = $this->faker->numberBetween(0, $total - 1);

        return $this->users[$key];
    }
}
