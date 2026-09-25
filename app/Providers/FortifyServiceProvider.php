<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   public function register(): void
{
    // Custom LoginResponse agar redirect sesuai Role user
    $this->app->instance(LoginResponse::class, new class implements LoginResponse {
        public function toResponse($request)
        {
            $redirectTarget = $request->query('redirect');

            if (filled($redirectTarget) && Str::startsWith($redirectTarget, 'http')) {
                return redirect()->to($redirectTarget);
            }

            $user = Auth::user();
            $role = $user?->role;

            if ($role === 'Guru' && $user->hasPiketToday()) {
                return redirect()->route('piket.dashboard');
            }

            return match ($role) {
                'Admin'       => redirect()->route('admin.dashboard'),
                'Guru'        => redirect()->route('guru.dashboard'),
                'Kesiswaan'   => redirect()->route('kesiswaan.dashboard'),
                'Sekretaris'  => redirect()->route('sekretaris.dashboard'),
                default       => redirect()->route('dashboard'),
            };
        }
    });

    $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
        public function toResponse($request)
        {
            return redirect()->route('login');
        }
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();

        Route::group([
            'namespace' => 'Laravel\Fortify\Http\Controllers',
            'domain' => config('fortify.domain', null),
            'prefix' => config('fortify.prefix'),
        ], function () {
            $this->loadRoutesFrom(base_path('vendor/laravel/fortify/routes/routes.php'));
        });

    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::resetPasswordView(fn () => view('auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip(),
            );
        });
    }
}