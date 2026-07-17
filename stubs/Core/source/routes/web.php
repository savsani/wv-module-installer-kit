<?php

use Illuminate\Support\Facades\Route;

// Example/showcase pages are intentionally public — the Core module must
// be usable to test its UI components without the Auth module installed
// or a logged-in session.
Route::get('/examples/form-elements', function () {
    return view('examples.form-elements');
})->name('examples.form-elements');

Route::get('/examples/data-table', function () {
    return view('examples.data-table');
})->name('examples.data-table');

Route::get('/examples/ui/alerts', function () {
    return view('examples.ui.alerts');
})->name('examples.ui.alerts');

Route::get('/examples/ui/buttons', function () {
    return view('examples.ui.buttons');
})->name('examples.ui.buttons');

Route::get('/examples/ui/badges', function () {
    return view('examples.ui.badges');
})->name('examples.ui.badges');

Route::get('/examples/ui/data-display', function () {
    return view('examples.ui.data-display');
})->name('examples.ui.data-display');

Route::get('/examples/ui/tabs', function () {
    return view('examples.ui.tabs');
})->name('examples.ui.tabs');

Route::get('/examples/ui/toasts', function () {
    return view('examples.ui.toasts');
})->name('examples.ui.toasts');

Route::get('/examples/ui/modals', function () {
    return view('examples.ui.modals');
})->name('examples.ui.modals');

Route::get('/examples/errors/403', function () {
    return response()->view('errors.403', [], 403);
})->name('examples.errors.403');

Route::get('/examples/errors/404', function () {
    return response()->view('errors.404', [], 404);
})->name('examples.errors.404');

Route::get('/examples/errors/500', function () {
    return response()->view('errors.500', [], 500);
})->name('examples.errors.500');

Route::get('/examples/errors/503', function () {
    return response()->view('errors.503', [], 503);
})->name('examples.errors.503');
