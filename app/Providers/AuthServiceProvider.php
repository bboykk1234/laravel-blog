<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(null, ['prefix' => 'api/oauth']);

        Auth::viaRequest('email', function (Request $request) {
            if ($request->get('email')) {
                return User::where('email', $request->get('email'))->first();
            }

            if ($request->input('username')) {
                return User::where('email', $request->input('username'))->first();
            }
        });

        Gate::define('isAdmin', function(User $user) {
            return $user->type === 1;
         });
    }
}
