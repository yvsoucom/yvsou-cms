<?php
// SPDX-FileCopyrightText: Laravel auto generate
//
// SPDX-License-Identifier: MIT
?> 

@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        {{ $status }}
    </div>
@endif
