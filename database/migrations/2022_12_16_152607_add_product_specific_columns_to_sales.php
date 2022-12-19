<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Add required columns
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable();
            $table->integer('margin')->nullable();
            $table->integer('shipping_cost')->nullable();
        });

        // Perform migration
        $default = Product::find(1);

        DB::statement("UPDATE sales SET product_id = 1");
        DB::statement("UPDATE sales SET margin = ?", [$default->margin]);
        DB::statement("UPDATE sales SET shipping_cost = ?", [$default->shipping_cost]);

        // Remove nullables
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable(false)->change();
            $table->integer('margin')->nullable(false)->change();
            $table->integer('shipping_cost')->nullable(false)->change();

            // Add foreign id
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('margin');
            $table->dropColumn('shipping_cost');
        });
    }
};
