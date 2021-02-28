<?php

namespace Strimoid\Models\Folders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Strimoid\Models\FakeFolder;

class Banned extends FakeFolder
{
    public bool $isPrivate = true;
    public function __construct(\Illuminate\Translation\Translator $translator, \Illuminate\Translation\Translator $translator, private \Illuminate\Auth\AuthManager $authManager, private \Illuminate\Routing\Redirector $redirector)
    {
        parent::__construct($translator);
        parent::__construct($translator);
    }

    protected function getBuilder(string $model): Builder
    {
        if ($this->authManager->guest()) {
            $this->redirector->guest('login');
        }

        $builder = with(new $model())->newQuery();

        $bannedGroups = $this->authManager->user()->bannedGroups()->pluck('id');
        $builder->whereIn('group_id', $bannedGroups);

        return $builder;
    }
}
