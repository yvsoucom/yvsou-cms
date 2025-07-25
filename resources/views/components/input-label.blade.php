<?php
// SPDX-FileCopyrightText: Laravel auto generate
//
// SPDX-License-Identifier: MIT
?> 
 
@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
