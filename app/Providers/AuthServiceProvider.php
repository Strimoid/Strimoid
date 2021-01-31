<?php

namespace Strimoid\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Group;
use Strimoid\Models\User;
use Strimoid\Policies\CommentPolicy;
use Strimoid\Policies\CommentReplyPolicy;
use Strimoid\Policies\ContentPolicy;
use Strimoid\Policies\EntryPolicy;
use Strimoid\Policies\EntryReplyPolicy;
use Strimoid\Policies\GroupPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Comment::class => CommentPolicy::class,
        CommentReply::class => CommentReplyPolicy::class,
        Content::class => ContentPolicy::class,
        Entry::class => EntryPolicy::class,
        EntryReply::class => EntryReplyPolicy::class,
        Group::class => GroupPolicy::class,
    ];

    public function boot(GateContract $gate): void
    {
        parent::registerPolicies();

        Passport::routes();

        $gate->before(function (User $user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }

            return null;
        });
    }
}
