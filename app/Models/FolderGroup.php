<?php namespace Strimoid\Models;

use Strimoid\Models\Traits\HasGroupRelationship;

class FolderGroup extends BaseModel
{
    use HasGroupRelationship;

    protected $table = 'folder_groups';
}
