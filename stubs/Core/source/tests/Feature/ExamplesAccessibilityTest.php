<?php

use App\Models\User;

test('examples pages are accessible without authentication', function (string $route) {
    $response = $this->get(route($route));

    $response->assertOk();
})->with([
    'examples.form-elements',
    'examples.data-table',
    'examples.ui.alerts',
    'examples.ui.buttons',
    'examples.ui.badges',
    'examples.ui.data-display',
    'examples.ui.tabs',
    'examples.ui.toasts',
    'examples.ui.modals',
]);

test('guests do not see the user menu or default sidebar links on example pages', function () {
    $response = $this->get(route('examples.form-elements'));

    $response->assertOk();
    $response->assertSee('Examples');
    $response->assertDontSee('My Profile');
});

test('authenticated users see the user menu and default sidebar links on example pages', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('examples.form-elements'));

    $response->assertOk();
    $response->assertSee($user->name);
});
