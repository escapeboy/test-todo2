<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'read-profile' => __('Read profile data'),
            'update-profile' => __('Update profile data'),
            'create-tasks' => __('Create tasks'),
            'see-lists' => __('See lists'),
            'complete-tasks' => __('Complete tasks'),
            'delete-tasks' => __('Delete tasks'),
            'create-lists' => __('Create lists'),
            'delete-lists' => __('Delete lists'),
        ]);
    }
}
