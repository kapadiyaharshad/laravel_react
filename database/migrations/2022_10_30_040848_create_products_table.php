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
            $table->string('name');
            $table->string('slug');
            $table->integer('category_id');
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
            $table->mediumText('small_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->string('brand');
            $table->decimal('selling_price',9,2);
            $table->decimal('original_price',9,2);
            $table->integer('qty');
            $table->string('image')->nullable();
            $table->tinyInteger('feature')->default(0)->nullable();
            $table->tinyInteger('popular')->default(0)->nullable();
            $table->tinyInteger('status')->default(0);
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
