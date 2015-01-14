<?php

use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Group;
use Strimoid\Models\User;

class FakeSeeder extends BaseSeeder
{

    private $users = [];

    public function run()
    {
        $this->cleanDatabase();

        // Make sure we will get same data every time
        $this->faker->seed(12345);

        for ($i = 0; $i < 50; $i++) {
            $this->createFakeUser();
        }
    }

    protected function cleanDatabase()
    {
        $tables = ['contents', 'entries', 'groups', 'users'];

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
            'created_at' => $this->faker->dateTimeThisDecade,
            'creator_id' => $creator->getKey(),
            'name' => $this->faker->city,
            'description' => implode(' ', $this->faker->sentences(2)),
            'sidebar' => $this->faker->paragraph,
            'tags' => $this->faker->words(5),
            'urlname' => $this->faker->domainWord,
        ]);

        $contentsNumber = $this->faker->numberBetween(0, 10);

        for ($i = 0; $i < $contentsNumber; $i++) {
            $this->createFakeContent($group);
            $this->createFakeEntry($group);
        }
    }

    protected function createFakeContent(Group $group)
    {
        Content::create([
            with (new Content)->getKeyName() => $this->getRandomId(),
            'created_at' => $this->faker->dateTimeThisDecade,
            'group_id' => $group->getKey(),
            'title' => $this->faker->sentence(10),
            'description' => $this->faker->text(200),
            'user_id' => $this->getRandomUser()->getKey(),
            'url' => $this->faker->url,
            'nsfw' => $this->faker->boolean,
            'eng' => $this->faker->boolean,
        ]);
    }

    protected function createFakeEntry(Group $group)
    {
        Entry::create([
            with(new Entry)->getKeyName() => $this->getRandomId(),
            'created_at' => $this->faker->dateTimeThisDecade,
            'group_id' => $group->getKey(),
            'text' => $this->faker->text(512),
            'user_id' => $this->getRandomUser()->getKey(),
        ]);
    }

    protected function getRandomId()
    {
        return substr($this->faker->unique()->sha1, 0, 6);
    }

    protected function getRandomUser()
    {
        $total = count($this->users);
        $key = $this->faker->numberBetween(0, $total - 1);

        return $this->users[$key];
    }

}
