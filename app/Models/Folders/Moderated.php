<?php

namespace Strimoid\Models\Folders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Moderated extends FakeFolder
{
    public bool $isPrivate = true;
    public function __construct(\Illuminate\Translation\Translator $translator, private \Illuminate\Auth\AuthManager $authManager)
    {
        parent::__construct($translator);
    }

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();

        $moderatedGroups = $this->authManager->user()->moderatedGroups()->select('groups.id')->pluck('id');
        $builder->whereIn('group_id', $moderatedGroups);

        return $builder;
    }
}
