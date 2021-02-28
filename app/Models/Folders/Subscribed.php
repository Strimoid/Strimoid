<?php

namespace Strimoid\Models\Folders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Subscribed extends FakeFolder
{
    public bool $isPrivate = true;
    public function __construct(\Illuminate\Translation\Translator $translator, private \Illuminate\Auth\AuthManager $authManager)
    {
        parent::__construct($translator);
    }

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();

        $subscribedGroups = $this->authManager->user()->subscribedGroups()->pluck('id');
        $builder->whereIn('group_id', $subscribedGroups);

        $blockedUsers = $this->authManager->user()->blockedUsers()->pluck('id');
        $builder->whereNotIn('user_id', $blockedUsers);

        return $builder;
    }
}
