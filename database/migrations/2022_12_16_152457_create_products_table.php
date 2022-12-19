<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->integer('margin');
            $table->integer('shipping_cost');

            $table->timestamps();
        });

        // Insert default products
        Product::query()->create([
            'id' => 1,
            'name' => 'Gold coffee',
            'margin' => '25',
            'shipping_cost' => '1000',
        ]);

        Product::query()->create([
            'id' => 2,
            'name' => 'Arabic coffee',
            'margin' => '15',
            'shipping_cost' => '1000',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
