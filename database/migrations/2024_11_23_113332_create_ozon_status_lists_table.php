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
        Schema::create('ozon_status_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ozon_status_name');
            $table->string('color')->nullable();
            $table->boolean('watch_label')->default(false);
            $table->boolean('watch_ozon_status')->default(false);
            $table->timestamps();
        });

        $data_items = [
            [
                'name' => 'Ожидают сборки',
                'ozon_status_name' => 'awaiting_packaging',
                'color' => '#fef6e0',
                'watch_label' => 1,
                'watch_ozon_status' => 1,
            ],
            [
                'name' => 'Ожидают отгрузки',
                'ozon_status_name' => 'awaiting_deliver',
                'color' => '#e1f4f6',
                'watch_label' => 1,
                'watch_ozon_status' => 1,
            ],
            [
                'name' => 'Доставляются',
                'ozon_status_name' => 'delivering',
                'color' => '#f1f1ff',
                'watch_label' => 0,
                'watch_ozon_status' => 1,
            ],
            [
                'name' => 'Спорные',
                'ozon_status_name' => 'arbitration',
                'color' => '#ffa500',
                'watch_label' => 0,
                'watch_ozon_status' => 1,
            ],
            [
                'name' => 'Отменены',
                'ozon_status_name' => 'cancelled',
                'color' => '#eff2f6',
                'watch_label' => 0,
                'watch_ozon_status' => 0,
            ],
            [
                'name' => 'Доставлены',
                'ozon_status_name' => 'delivered',
                'color' => '#e3f5e8',
                'watch_label' => 0,
                'watch_ozon_status' => 0,
            ],
        ];
        foreach ($data_items as $item) {
            DB::table('ozon_status_lists')->insert($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ozon_status_lists');
    }
};
