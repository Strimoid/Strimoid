<?php namespace Strimoid\Contracts; 

interface FolderRepository {

    /**
     * Get folder with given name.
     *
     * @param  $ownerName   string  Name of folder owner
     * @param  $folderName  string  Folder name
     * @return \Strimoid\Models\Folder
     */
    public function getByName($ownerName, $folderName);

}
