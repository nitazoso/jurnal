<?php

namespace App\Providers;

use App\Models\AcademicPeriod;
use App\Models\Jadwal;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
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
        $this->configureDefaults();

        View::composer(['layouts.guru', 'layouts.sekretaris'], function ($view) {
            $period = AcademicPeriod::current() ?? Jadwal::latestAcademicPeriod();
            $label = $period
                ? trim($period->semester.' '.$period->tahun_ajaran)
                : 'Tahun ajaran belum diatur';

            $view->with('tahunAjaranAktif', $label);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
