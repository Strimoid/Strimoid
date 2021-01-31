<?php

namespace Strimoid\Repositories;

use Strimoid\Contracts\Repositories\FolderRepository as FolderRepositoryContract;
use Strimoid\Contracts\Repositories\UserRepository as UserRepositoryContract;
use Strimoid\Models\Folder;

class FolderRepository extends Repository implements FolderRepositoryContract
{
    protected Folder $folder;

    protected UserRepositoryContract $users;

    public function __construct(Folder $folder, UserRepositoryContract $users)
    {
        $this->folder = $folder;
        $this->users = $users;
    }

    public function getByName(string $ownerName, string $folderName): ?Folder
    {
        $user = $this->users->getByName($ownerName);

        if (!$user) {
            return null;
        }

        return $user->folders()
            ->with('user')
            ->where('name', $folderName)
            ->first();
    }
}
