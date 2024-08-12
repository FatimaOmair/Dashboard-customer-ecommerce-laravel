<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->decimal('regular_price', 8, 2);
            $table->string('SKU')->unique();
            $table->enum('stock_status', ['instock', 'outofstock'])->default('instock');
            $table->integer('quantity')->default(0); // Ensure quantity has a default value
            $table->string('image')->nullable();
            $table->json('images')->nullable()->default(json_encode([])); // Ensure this is a JSON column with a default value
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
