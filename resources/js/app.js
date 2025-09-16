// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025  
// SPDX-License-Identifier: MIT  

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Conditionally load editor.js if global flag is set
if (window.shouldLoadEditor) {
    import('./editor.js')
        .then(() => {
            console.log('Editor loaded');
        })
        .catch(err => {
            console.error('Failed to load editor:', err);
        });
}
if (window.shouldLoadEditor) {
    import('../css/editor.css');
}
