<?php namespace Strimoid\Repositories; 

use Strimoid\Contracts\Repositories\FolderRepository as FolderRepositoryContract;
use Strimoid\Contracts\Repositories\UserRepository;
use Strimoid\Models\Folder;

class FolderRepository implements FolderRepositoryContract {

    /**
     * @var Folder
     */
    protected $folder;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param Folder         $folder
     * @param UserRepository $users
     */
    public function __construct(Folder $folder, UserRepository $users)
    {
        $this->folder = $folder;
        $this->users = $users;
    }

    /**
     * @inheritdoc
     */
    public function getByName($userName, $folderName)
    {
        $user = $this->users->getByName($userName);

        if ( ! $user) return;

        return $this->folder->findUserFolder($user->getKey(), $folderName);
    }

}
