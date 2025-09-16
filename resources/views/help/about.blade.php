<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>
@extends('layouts.app')

@section('content')

    <div class="text-left mt-12">
        <main class="prose dark:prose-invert 
                    max-w-3xl mx-auto p-8
                    bg-white dark:bg-gray-900
                    text-gray-800 dark:text-gray-200
                    rounded-lg shadow
                    transition-colors duration-200">
            {!!  $aboutMdHtml  !!}
        </main>
    </div>

@endsection