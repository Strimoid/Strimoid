<?php

namespace Strimoid\Contracts\Repositories;

use Strimoid\Exceptions\EntityNotFoundException;
use Strimoid\Models\Folder;

interface FolderRepository
{
    public function getByName(string $ownerName, string $folderName): ?Folder;

    /** @throws EntityNotFoundException */
    public function requireByName(...$params);
}
