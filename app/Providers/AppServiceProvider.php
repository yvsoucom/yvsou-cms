<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.,     All rights reserved.
 * Author: Lican Huang
 *
 * SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
 * License: Dual Licensed – GPLv3 or Commercial
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * As an alternative to GPLv3, commercial licensing is available for organizations
 * or individuals requiring proprietary usage, private modifications, or support.
 *
 * Contact: yvsoucom@gmail.com
 * GPL License: https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace App\Providers;
use App\Http\Middleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use App\Services\ConstantService;
use App\Services\LocaleService;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\View;
use App\Models\MailSetting;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->role === 'admin'; // or $user->is_admin, etc.
        });


        try {
            if (Schema::hasTable('mail_settings')) {
                $settings = MailSetting::getSettings();

                config([
                    'mail.mailers.smtp.host' => $settings['host'] ?? null,
                    'mail.mailers.smtp.port' => $settings['port'] ?? null,
                    'mail.mailers.smtp.encryption' => $settings['encryption'] ?? null,
                    'mail.mailers.smtp.username' => $settings['username'] ?? null,
                    'mail.mailers.smtp.password' => $settings['password'] ?? null,
                    'mail.from.address' => $settings['from_address'] ?? null,
                    'mail.from.name' => $settings['from_name'] ?? null,
                ]);
            } else {
                // Optional: log or use default mail config
                logger('mail_settings table does not exist.');
            }
        } catch (\Throwable $e) {
            logger()->error('Error loading mail settings: ' . $e->getMessage());
            // Optional: fallback config
        }


        if (app()->runningInConsole() && basename($_SERVER['PHP_SELF']) === 'generate_migrations_from_models.php') {
            return; // prevent loading shortcodes
        }
        // app(LocaleService::class)->setbootLocaleFromCookie();
        ConstantService::$adminHasAllRights = config('yvsou_config.ADMINHASRIGHTS') ?? false;
        View::composer('*', function ($view) {
            $localeService = app(LocaleService::class);
            // $view->with('getlangSet', $localeService->getlangSet(config('yvsou_config.LANGUAGESET')));
            $view->with('getlangSet', $localeService->getlangSet(config('yvsou_config.LANGUAGESET')));
        });
        try {
            /*
            if (Schema::hasTable('shortcodes')) {
                $shortcodes = \App\Models\Shortcode::all();
                $shortcodeManager = new \App\Services\ShortcodeManager();

                app()->instance('shortcode', $shortcodeManager);
            } else {
                // Optional: log or use default mail config
                logger('shortcodes table does not exist.');
            }*/

        } catch (\Throwable $e) {
            logger()->error('Error shortcodes: ' . $e->getMessage());
            // Optional: fallback config
        }

    }
}

