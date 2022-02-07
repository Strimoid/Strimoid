<?php

namespace Strimoid\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Models\ContentRelated;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Group;
use Strimoid\Models\User;
use Strimoid\Policies\CommentPolicy;
use Strimoid\Policies\CommentReplyPolicy;
use Strimoid\Policies\ContentPolicy;
use Strimoid\Policies\ContentRelatedPolicy;
use Strimoid\Policies\EntryPolicy;
use Strimoid\Policies\EntryReplyPolicy;
use Strimoid\Policies\GroupPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        CommentReply::class => CommentReplyPolicy::class,
        Comment::class => CommentPolicy::class,
        Content::class => ContentPolicy::class,
        ContentRelated::class => ContentRelatedPolicy::class,
        EntryReply::class => EntryReplyPolicy::class,
        Entry::class => EntryPolicy::class,
        Group::class => GroupPolicy::class,
    ];

    public function boot(GateContract $gate): void
    {
        $this->registerPolicies();

        $gate->before(function ($user, $ability) {
            dd('test');

            if ($user->isSuperAdmin()) {
                return true;
            }

            return null;
        });

        Passport::routes();
    }
}
