<?php

namespace Strimoid\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Strimoid\Models\User;
use Symfony\Component\Console\Input\InputArgument;

class DeleteUser extends Command
{
    protected $name = 'lara:deleteuser';
    protected $description = 'Deletes user.';

    public function handle(): void
    {
        $user = User::findOrFail($this->argument('username'));

        if ($this->confirm('Do you really want to remove user: ' . $user->name . '? [yes|no]')) {
            $user->removed_at = Carbon::now();
            $user->type = 'deleted';
            $user->unset(['age', 'description', 'email', 'last_login', 'last_ip',
                'location', 'password', 'settings', 'sex', 'shadow_email', ]);
            $user->deleteAvatar();

            $user->save();

            $this->info('User deleted');
        }

        /*
        foreach (Content::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (Entry::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (EntryReply::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (Comment::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (CommentReply::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (ContentRelated::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();
        */
    }

    protected function getArguments(): array
    {
        return [
            ['username', InputArgument::REQUIRED, 'User name.'],
        ];
    }
}
