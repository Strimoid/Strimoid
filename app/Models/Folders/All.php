<?php

namespace Strimoid\Models\Folders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\Content;
use Strimoid\Models\FakeFolder;

class All extends FakeFolder
{
    public function __construct(\Illuminate\Translation\Translator $translator, private \Illuminate\Auth\AuthManager $authManager)
    {
        parent::__construct($translator);
    }
    protected function getBuilder(string $model): Builder
    {
        $builder = with(new $model())->newQuery();

        if ($this->authManager->check()) {
            $blockedGroups = $this->authManager->user()->blockedGroups()->pluck('id');
            $builder->whereNotIn('group_id', $blockedGroups);

            $blockedUsers = $this->authManager->user()->blockedUsers()->pluck('id');
            $builder->whereNotIn('user_id', $blockedUsers);
        }

        return $builder;
    }

    public function contents(string $tab = null, string $sortBy = null): Builder
    {
        $builder = $this->getBuilder(Content::class);

        if ($this->authManager->check()) {
            $blockedDomains = $this->authManager->user()->blockedDomains();
            $builder->whereNotIn('domain', $blockedDomains);
        }

        if ($tab === 'new') {
            $builder->frontpage(false);
        } elseif ($tab === 'popular') {
            $builder->frontpage(true);
            $sortBy = $sortBy ?: 'frontpage_at';
        }

        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }
}
