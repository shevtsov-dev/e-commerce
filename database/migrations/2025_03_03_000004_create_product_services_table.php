<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductServicesTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('product_services', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned()->index();
            $table->bigInteger('service_id')->unsigned()->index();
            $table->timestamps();

            $table->primary(['product_id', 'service_id']);
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')
                ->onDelete('cascade');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('product_services', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('product_services');
    }
}
