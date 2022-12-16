<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesTest extends TestCase
{
    use RefreshDatabase;

    public function testRejectsGuestUsers()
    {
        $response = $this->get(route('coffee.sales'));

        $response->assertRedirect(route('login'));
    }

    public function testPageRendersForAuthenticedUsers()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('coffee.sales'));

        $response->assertSuccessful();
    }

    public function testPageContainsRecordSaleComonent()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('coffee.sales'));

        $response->assertSeeLivewire('record-sale');
    }

    public function testPageContainsPreviousSalesComponent()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('coffee.sales'));

        $response->assertSeeLivewire('show-previous-sales');
    }
}
