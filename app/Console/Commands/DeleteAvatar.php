<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Strimoid\Models\User;
use Symfony\Component\Console\Input\InputArgument;

class DeleteAvatar extends Command
{
    /** @var string */
    protected $name = 'lara:delavatar';

    /** @var string */
    protected $description = 'Deletes user avatar.';

    public function fire(): void
    {
        if (!$this->argument('username')) {
            $this->error('no username given');
            return;
        }

        $user = User::findOrFail($this->argument('username'));
        $user->deleteAvatar();
        $user->save();
    }

    protected function getArguments(): array
    {
        return [
            ['username', InputArgument::REQUIRED, 'User name.'],
        ];
    }
}
