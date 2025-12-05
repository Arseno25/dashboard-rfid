<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useTailwind();
        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin();
        });

        View::composer('*', function ($view) {
            static $sharedSettings = null;

            if ($sharedSettings === null) {
                $sharedSettings = [
                    'name' => setting_value('general_site_name', config('app.name', 'Zarly Petshop')),
                    'tagline' => setting_value('general_tagline', 'Inventory & Experience Hub'),
                    'hero_title' => setting_value('general_homepage_title', 'Kurasi kebutuhan hewan kesayangan dalam satu dasbor elegan.'),
                    'hero_subtitle' => setting_value('general_homepage_subtitle', 'Pantau stok, promo, dan performa produk dengan tampilan yang rapi agar pelanggan menemukan perlengkapan terbaiknya.'),
                    'brand_logo_path' => setting_value('brand_logo_path'),
                    'brand_favicon_path' => setting_value('brand_favicon_path'),
                    'contact_email' => setting_value('contact_email', 'hello@zarlypetshop.id'),
                    'contact_phone' => setting_value('contact_phone', '+62 812 3456 789'),
                    'contact_whatsapp' => setting_value('contact_whatsapp'),
                    'contact_address' => setting_value('contact_address', 'Jl. Halimun No. 17, Jakarta'),
                    'meta_description' => setting_value('meta_description', 'Dashboard curated inventory & checkout experience untuk retail modern.'),
                    'meta_keywords' => setting_value('meta_keywords'),
                ];
            }

            $view->with('siteSettings', $sharedSettings);
        });
    }
}
