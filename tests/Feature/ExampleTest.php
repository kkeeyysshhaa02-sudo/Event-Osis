<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response for events index', function () {
    $response = $this->get('/events');

    $response->assertStatus(200);
});
