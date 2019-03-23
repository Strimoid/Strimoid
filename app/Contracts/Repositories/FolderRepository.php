<?php

namespace Strimoid\Contracts\Repositories;

use Strimoid\Models\Folder;

interface FolderRepository
{
    public function getByName(string $ownerName, string $folderName): ?Folder;

    /** @throws \Strimoid\Exceptions\EntityNotFoundException */
    public function requireByName(...$params);
}
