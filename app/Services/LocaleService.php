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


namespace App\Services;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LocaleService
{
    public function setbootLocaleFromCookie(): void
    {
        if (!request()->isMethod('get') || !request()->acceptsHtml()) {
            return; // Only apply locale on normal HTML page GET requests
        }
        $cookieLocale = Cookie::get('locale');
        logger('setbootLocaleFromCookie begin  ', [$cookieLocale]); // Temporarily check this
        logger('setbootLocaleFromCookie getLocale   ', [app()->getLocale()]); // Temporarily check this

        if (!$cookieLocale)
            $cookieLocale = config('app.locale');

        if (!in_array($cookieLocale, config('yvsou_config.LANGUAGESET'))) {
            $Locale = app()->getLocale();
            logger('setbootLocaleFromCookie getLocale not in array  ', [$Locale]); // Temporarily check this

            if (!$Locale) {

                $Locale = config('app.locale');
                //  App::setLocale($Locale);
            }
        } else {
            logger('setbootLocaleFromCookie cookieLocale ', [$cookieLocale]); // Temporarily check this
            logger('setbootLocaleFromCookie getLocale ', [app()->getLocale()]); // Temporarily check this

            if (app()->getLocale() !== $cookieLocale)
                app()->setLocale($cookieLocale);
        }
        logger('setbootLocaleFromCookie after', [app()->getLocale()]); // Temporarily check this

    }

    public function getlangSet($langSet): array
    {
        $languageArray = [];
        $langSet = $langSet ?? [];
        foreach ($langSet as $code) {

            $code = trim($code);

            $languageArray[$code] = ConstantService::LANGUAGE_CONFIG[$code]['native'];

        }
        return $languageArray;
    }

    public function getlangIdSet(): array
    {
        $langidArray = [];
        $langset = config('yvsou_config.LANGUAGESET', []);
        foreach ($langset as $code) {

            $langid = $this->getlangID($code);
            $language = ConstantService::LANGUAGE_CONFIG[$code]['native'];
            $langidArray[] = ['langid' => $langid, 'language' => $language];
        }
        return $langidArray;
    }

    public function getlangID($code): int
    {

        return ConstantService::LANGUAGE_CONFIG[$code]['id'];

    }



    public function getcurlang(): int
    {
        //  $this->getSetLocaleFromCookie();
        $code = App::getLocale();
        $langid = $this->getlangID($code);
        //logger('langcode, langid ', [$code, $langid]);
        return $langid;

    }
    public function getcurlangcode(): string
    {
        // $this->getSetLocaleFromCookie();
        $code = App::getLocale();
        return $code;

    }


}






