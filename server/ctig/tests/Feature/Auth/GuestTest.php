<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class GuestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_guest_not_allowed_main_points(): void{
        $this->get(route('exams.index'))
            ->assertRedirect(route('login'));

        $this->get(route('foreign-nationals.index'))
            ->assertRedirect(route('login'));
    }
}
