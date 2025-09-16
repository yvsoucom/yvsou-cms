<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.    All rights reserved.
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

class ConstantService
{

    // Define all supported languages
    const  LANGUAGE_CONFIG = [
        'en' => [
            'id' => 30,
            'native' => 'English',
            'regional' => ['en-US', 'en-GB', 'en-AU']
        ],
        'zh' => [
            'id' => 1,
            'native' => '简体中文',
            'regional' => ['zh-Hans']
        ],
        'zh-TW' => [
            'id' => 2,
            'native' => '繁體中文',
            'regional' => ['zh-Hant']
        ],
        'ja' => [
            'id' => 5,
            'native' => '日本語',
            'regional' => ['ja-JP']
        ],
        'ko' => [
            'id' => 10,
            'native' => '한국어',
            'regional' => ['ko-KR']
        ],
        'es' => [
            'id' => 6,
            'native' => 'Español',
            'regional' => ['es-ES', 'es-MX']
        ],
        'fr' => [
            'id' => 3,
            'native' => 'Français',
            'regional' => ['fr-FR', 'fr-CA']
        ],
        'de' => [
            'id' => 4,
            'native' => 'Deutsch',
            'regional' => ['de-DE', 'de-AT']
        ],
        'ru' => [
            'id' => 7,
            'native' => 'Русский',
            'regional' => ['ru-RU']
        ],
        'ar' => [
            'id' => 12,
            'native' => 'العربية',
            'regional' => ['ar-AE', 'ar-SA']
        ],
        'pt' => [
            'id' => 9,
            'native' => 'Português',
            'regional' => ['pt-PT', 'pt-BR']
        ],
        'it' => [
            'id' => 8,
            'native' => 'Italiano',
            'regional' => ['it-IT']
        ],
        'vi' => [
            'id' => 11,
            'native' => 'Tiếng Việt',
            'regional' => ['vi-VN']
        ],
        'hi' => [
            'id' => 13,
            'native' => 'हिन्दी',
            'regional' => ['hi-IN']
        ],
        'tr' => [
            'id' => 14,
            'native' => 'Türkçe',
            'regional' => ['tr-TR']
        ]
    ];
    const SUPPORTEDLOCALES = ['en', 'ja', 'zh', 'fr'];

     
    public static bool $alist = false;
    public static int $slen = 150;
    public static bool $adminHasAllRights = true;

}