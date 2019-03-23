<?php

namespace Strimoid\Repositories;

use Strimoid\Contracts\Repositories\FolderRepository as FolderRepositoryContract;
use Strimoid\Contracts\Repositories\UserRepository as UserRepositoryContract;
use Strimoid\Models\Folder;

class FolderRepository extends Repository implements FolderRepositoryContract
{
    /**
     * @var Folder
     */
    protected $folder;

    /**
     * @var UserRepository
     */
    protected $users;

    public function __construct(Folder $folder, UserRepositoryContract $users)
    {
        $this->folder = $folder;
        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    public function getByName(string $userName, string $folderName): ?Folder
    {
        $user = $this->users->getByName($userName);

        if (!$user) {
            return null;
        }

        return $this->folder->findUserFolder($user->getKey(), $folderName);
    }
}
