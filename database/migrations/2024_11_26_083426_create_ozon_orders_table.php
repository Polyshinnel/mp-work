<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ozon_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_status_id');
            $table->unsignedBigInteger('site_label_id');
            $table->unsignedBigInteger('ozon_status_id');
            $table->unsignedBigInteger('ozon_warehouse_id');
            $table->unsignedBigInteger('site_id');
            $table->string('ozon_order_id');
            $table->string('ozon_posting_id');
            $table->integer('products_count');
            $table->string('site_order_id');
            $table->dateTime('date_order_create');
            $table->timestamps();

            $table->index('site_status_id', 'ozon_orders_site_status_id_idx');
            $table->foreign('site_status_id', 'ozon_orders_site_status_id_fk')
                ->on('site_status_lists')
                ->references('id');

            $table->index('site_label_id', 'ozon_orders_site_label_id_idx');
            $table->foreign('site_status_id', 'ozon_orders_site_label_id_fk')
                ->on('site_labels')
                ->references('id');

            $table->index('ozon_status_id', 'ozon_orders_ozon_status_id_idx');
            $table->foreign('ozon_status_id', 'ozon_orders_ozon_status_id_fk')
                ->on('ozon_status_lists')
                ->references('id');

            $table->index('ozon_warehouse_id', 'ozon_orders_ozon_warehouse_id_idx');
            $table->foreign('ozon_warehouse_id', 'ozon_orders_ozon_warehouse_id_fk')
                ->on('ozon_warehouses')
                ->references('id');

            $table->index('site_id', 'ozon_orders_site_id_idx');
            $table->foreign('site_id', 'ozon_orders_ozon_site_id_fk')
                ->on('sites')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ozon_orders');
    }
};
