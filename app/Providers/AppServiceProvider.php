<?php

namespace App\Providers;

use App\Contracts\GetCommentStrategyForDiffRole;
use App\Contracts\ListArticleStrategyForDiffRole;
use App\Contracts\ShowArticleDetailsStrategyForDiffRole;
use App\Strategies\GetCommentStrategyForAdmin;
use App\Strategies\GetCommentStrategyForNormalUser;
use App\Strategies\ListArticleStrategyForAdmin;
use App\Strategies\ListArticleStrategyForGuest;
use App\Strategies\ListArticleStrategyForNonAdmin;
use App\Strategies\ListArticleStrategyForNormalUser;
use App\Strategies\ShowArticleDetailsStrategyForAdmin;
use App\Strategies\ShowArticleDetailsStrategyForGuest;
use App\Strategies\ShowArticleDetailsStrategyForNormalUser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ListArticleStrategyForDiffRole::class, function(Application $app) {
            $req = $app['request'];
            $user = $req->user();

            if ($user !== null && $user->type === 1) {
                // Admin
                return new ListArticleStrategyForAdmin($user);
            }

            // Normal user or Guest
            return new ListArticleStrategyForNonAdmin($user);
        });

        $this->app->bind(ShowArticleDetailsStrategyForDiffRole::class, function(Application $app) {
            $req = $app['request'];
            $user = $req->user();

            if ($user === null) {
                return new ShowArticleDetailsStrategyForGuest($user);
            }

            if ($user->type === 1) {
                // Admin
                return new ShowArticleDetailsStrategyForAdmin($user);
            } elseif ($user->type === 0) {
                // Normal user
                return new ShowArticleDetailsStrategyForNormalUser($user);
            }

            // Guest
            return new ShowArticleDetailsStrategyForGuest($user);
        });

        $this->app->bind(GetCommentStrategyForDiffRole::class, function(Application $app) {
            $req = $app['request'];
            $user = $req->user();

            if ($user->type === 1) {
                // Admin
                return new GetCommentStrategyForAdmin($user);
            } elseif ($user->type === 0) {
                // Normal user
                return new GetCommentStrategyForNormalUser($user);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
