<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\RecordSale;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Tests\TestCase;

class RecordSaleTest extends TestCase
{
    use RefreshDatabase;

    public function testComponentRenders()
    {
        $component = Livewire::test(RecordSale::class);

        $component->assertStatus(200);
    }

    public function testQuantityIsRequired()
    {
        Livewire::test(RecordSale::class, ['quantity' => ''])
            ->call('store')
            ->assertHasErrors(['quantity' => 'required']);
    }

    public function testQuantityMustBePositive()
    {
        Livewire::test(RecordSale::class, ['quantity' => '-1'])
            ->call('store')
            ->assertHasErrors(['quantity' => 'gt:0']);
    }

    public function testQuantityMustBeInteger()
    {
        Livewire::test(RecordSale::class, ['quantity' => 'not-an-integer'])
            ->call('store')
            ->assertHasErrors(['quantity' => 'integer']);
    }

    public function testUnitCostIsRequired()
    {
        Livewire::test(RecordSale::class, ['unitCost' => ''])
            ->call('store')
            ->assertHasErrors(['unitCost' => 'required']);
    }

    public function testUnitCostMustBePositive()
    {
        Livewire::test(RecordSale::class, ['unitCost' => '-1'])
            ->call('store')
            ->assertHasErrors(['unitCost' => 'gt:0']);
    }

    public function testUnitCostMustBeNumeric()
    {
        Livewire::test(RecordSale::class, ['unitCost' => 'not-a-number'])
            ->call('store')
            ->assertHasErrors(['unitCost' => 'numeric']);
    }

    public function testMarginIsPulledFromConfiguration()
    {
        Config::set('sales.margin', 57);

        $margin = Livewire::test(RecordSale::class)
            ->get('margin');

        $this->assertEquals(57, $margin);
    }

    public function testShippingCostIsPulledFromConfiguration()
    {
        Config::set('sales.shipping_cost', 999);

        $shippingCost = Livewire::test(RecordSale::class)
            ->get('shippingCost');

        $this->assertEquals(999, $shippingCost);
    }

    public function testSellingPriceIsCalculated()
    {
        $sellingPrice = Livewire::test(RecordSale::class, ['quantity' => '1', 'unitCost' => '10.00'])
            ->get('sellingPrice');
        $this->assertEquals(23.33, $sellingPrice);

        $sellingPrice = Livewire::test(RecordSale::class, ['quantity' => '2', 'unitCost' => '20.50'])
            ->get('sellingPrice');
        $this->assertEquals(64.67, $sellingPrice);

        $sellingPrice = Livewire::test(RecordSale::class, ['quantity' => '5', 'unitCost' => '12'])
            ->get('sellingPrice');
        $this->assertEquals(90, $sellingPrice);
    }

    public function testEventIsEmittedOnStore()
    {
        Livewire::test(RecordSale::class, ['quantity' => '1', 'unitCost' => '10.00'])
            ->call('store')
            ->assertEmitted('saleCreated');
    }

    public function testSaleIsStored()
    {
        $this->assertDatabaseCount('sales', 0);

        Livewire::test(RecordSale::class, ['quantity' => '2', 'unitCost' => '10.00'])
            ->call('store');

        $this->assertDatabaseCount('sales', 1);

        $sale = Sale::all()->first();

        $this->assertEquals(10, $sale->unit_cost);
        $this->assertEquals(2, $sale->quantity);
        $this->assertEquals(36.67, $sale->selling_price);
    }
}
