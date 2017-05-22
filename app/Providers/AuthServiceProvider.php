<?php namespace Strimoid\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Strimoid\Models\Comment;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Group;
use Strimoid\Models\User;
use Strimoid\Policies\CommentPolicy;
use Strimoid\Policies\ContentPolicy;
use Strimoid\Policies\EntryPolicy;
use Strimoid\Policies\GroupPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $policies = [
        Comment::class => CommentPolicy::class,
        Content::class => ContentPolicy::class,
        Entry::class   => EntryPolicy::class,
        Group::class   => GroupPolicy::class,
    ];

    public function boot(GateContract $gate)
    {
        parent::registerPolicies();

        Passport::routes();

        $gate->before(function (User $user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
