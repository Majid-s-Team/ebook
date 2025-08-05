<?php

namespace App\Providers;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\ReelPolicy;
use App\Policies\ReelCommentPolicy;
use App\Policies\BookCategoryPolicy;
use App\Policies\BookPolicy;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Reel::class => ReelPolicy::class,
        ReelComment::class => ReelCommentPolicy::class,
        BookCategory::class => BookCategoryPolicy::class,
        Book::class => BookPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
