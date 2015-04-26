<?php namespace Strimoid\Contracts\Repositories;

interface FolderRepository
{
    /**
     * Get folder with given name.
     *
     * @param  $ownerName   string  Name of folder owner
     * @param  $folderName  string  Folder name
     *
     * @return \Strimoid\Models\Folder
     */
    public function getByName($ownerName, $folderName);

    /**
     * Get folder with given name and throw exception if not found.
     *
     * @param  $ownerName   string  Name of folder owner
     * @param  $folderName  string  Folder name
     *
     * @return \Strimoid\Models\Folder
     */
    public function requireByName($ownerName, $folderName);
}
