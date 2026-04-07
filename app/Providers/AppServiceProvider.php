<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Filiado; // Importe o Model
use App\Policies\FiliadoPolicy; // Importe a Policy

class AppServiceProvider extends AuthServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Filiado::class => FiliadoPolicy::class, // Associa Filiado à FiliadoPolicy
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return url(config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}");
        });

        //
    }
}