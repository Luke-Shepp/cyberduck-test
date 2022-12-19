<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\RecordSale;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function testProductShouldExist()
    {
        Livewire::test(RecordSale::class, ['quantity' => 1, 'unitCost' => 1, 'product' => 999999])
            ->call('store')
            ->assertHasErrors(['product' => 'exists']);
    }

    public function testQuantityIsRequired()
    {
        Livewire::test(RecordSale::class)
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

    public function testMarginIsPulledFromSelectedProduct()
    {
        $product = Product::factory()->create(['margin' => 56]);

        $margin = Livewire::test(RecordSale::class, ['product' => $product->id])
            ->get('margin');

        $this->assertEquals(56, $margin);
    }

    public function testShippingCostIsPulledFromSelectedProduct()
    {
        $product = Product::factory()->create(['shipping_cost' => 998]);

        $shippingCost = Livewire::test(RecordSale::class, ['product' => $product->id])
            ->get('shippingCost');

        $this->assertEquals(998, $shippingCost);
    }

    public function testSellingPriceIsCalculated()
    {
        $product = Product::factory()->create(['margin' => 25, 'shipping_cost' => 1000]);

        $sellingPrice = Livewire::test(RecordSale::class, ['product' => $product->id, 'quantity' => '1', 'unitCost' => '10.00'])
            ->get('sellingPrice');
        $this->assertEquals(23.33, $sellingPrice);

        $sellingPrice = Livewire::test(RecordSale::class, ['product' => $product->id, 'quantity' => '2', 'unitCost' => '20.50'])
            ->get('sellingPrice');
        $this->assertEquals(64.67, $sellingPrice);

        $sellingPrice = Livewire::test(RecordSale::class, ['product' => $product->id, 'quantity' => '5', 'unitCost' => '12'])
            ->get('sellingPrice');
        $this->assertEquals(90, $sellingPrice);


        $product = Product::factory()->create(['margin' => 15, 'shipping_cost' => 1000]);

        $sellingPrice = Livewire::test(RecordSale::class, ['product' => $product->id, 'quantity' => '1', 'unitCost' => '10.00'])
            ->get('sellingPrice');
        $this->assertEquals(21.76, $sellingPrice);

        $sellingPrice = Livewire::test(RecordSale::class, ['product' => $product->id, 'quantity' => '2', 'unitCost' => '20.50'])
            ->get('sellingPrice');
        $this->assertEquals(58.24, $sellingPrice);

        $sellingPrice = Livewire::test(RecordSale::class, ['product' => $product->id, 'quantity' => '5', 'unitCost' => '12'])
            ->get('sellingPrice');
        $this->assertEquals(80.59, $sellingPrice);
    }

    public function testSellingPriceIsOutputInComponent()
    {
        $component = Livewire::test(RecordSale::class, ['quantity' => '1', 'unitCost' => '10.00']);
        $component->assertSee('23.33');
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

        $product = Product::factory()->create(['margin' => 10, 'shipping_cost' => 1250]);

        Livewire::test(RecordSale::class, ['product' => $product->id, 'quantity' => '2', 'unitCost' => '10.00'])
            ->call('store');

        $this->assertDatabaseCount('sales', 1);

        $sale = Sale::all()->first();

        $this->assertEquals(10, $sale->unit_cost);
        $this->assertEquals($product->id, $sale->product_id);
        $this->assertEquals($product->margin, $sale->margin);
        $this->assertEquals($product->shipping_cost, $sale->shipping_cost);
        $this->assertEquals(2, $sale->quantity);
        $this->assertEquals(34.72, $sale->selling_price);
    }
}
