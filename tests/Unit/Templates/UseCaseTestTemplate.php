<?php

use Illuminate\Support\Facades\Event;

it('template: usecase test structure', function () {
    Event::fake();

    expect(true)->toBeTrue();
})->skip('Template only. Copy this file and adapt.');
