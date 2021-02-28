<?php

namespace Strimoid\Models\Folders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\Content;
use Strimoid\Models\FakeFolder;

class Blocked extends FakeFolder
{
    public bool $isPrivate = true;
    public function __construct(\Illuminate\Translation\Translator $translator, private \Illuminate\Auth\AuthManager $authManager)
    {
        parent::__construct($translator);
    }

    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();

        $blockedGroups = $this->authManager->user()->blockedGroups()->pluck('id');
        $builder->whereIn('group_id', $blockedGroups);

        $blockedUsers = $this->authManager->user()->blockedUsers()->pluck('id');
        $builder->orWhereIn('user_id', $blockedUsers);

        return $builder;
    }

    public function contents(string $tab = null, string $sortBy = null): Builder
    {
        $builder = $this->getBuilder(Content::class);

        $blockedDomains = $this->authManager->user()->blockedDomains();
        $builder->orWhereIn('domain', $blockedDomains);

        if ($tab === 'popular') {
            $builder->popular();
        }
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }
}
