<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id');
            $table->foreignId('brand_id');
            $table->string('name_th');
            $table->string('name_en')->nullable();
            $table->string('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->float('weight', 4, 2)->nullable();
            $table->float('length', 4, 2)->nullable();
            $table->float('width', 4, 2)->nullable();
            $table->float('depth', 4, 2)->nullable();
            $table->timestamps();
        });
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
}
