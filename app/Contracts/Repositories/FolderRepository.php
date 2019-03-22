<?php

namespace Strimoid\Contracts\Repositories;

interface FolderRepository
{
    /**
     * Get folder with given name.
     *
     * @param  $ownerName   string  Name of folder owner
     * @param  $folderName  string  Folder name
     *
     */
    public function getByName($ownerName, $folderName): \Strimoid\Models\Folder;

    /**
     * Get folder with given name and throw exception if not found.
     *
     *
     */
    public function requireByName(...$params): \Strimoid\Models\Folder;
}
