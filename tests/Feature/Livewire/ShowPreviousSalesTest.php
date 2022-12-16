<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ShowPreviousSales;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowPreviousSalesTest extends TestCase
{
    use RefreshDatabase;

    public function testTheComponentRenders()
    {
        Livewire::test(ShowPreviousSales::class)
            ->assertStatus(200);
    }

    public function testHandlesNoSales()
    {
        Livewire::test(ShowPreviousSales::class)
            ->assertSee('No Sales');
    }

    public function testShowsAllSales()
    {
        Sale::factory(3)->create();

        $component = Livewire::test(ShowPreviousSales::class);
        $component->assertDontSee('No Sales');

        $this->assertCount(3, $component->get('sales'));

        $component->assertSee($component->get('sales')->first()->unit_price);
        $component->assertSee($component->get('sales')->first()->product->name);
        $component->assertSee($component->get('sales')->last()->unit_price);
        $component->assertSee($component->get('sales')->last()->product->name);
    }
}
