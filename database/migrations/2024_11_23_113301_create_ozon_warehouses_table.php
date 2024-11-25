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
        Schema::create('ozon_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('warehouse_id');
            $table->timestamps();
        });

        $data_items = [
            [
                'name' => 'Ярославская',
                'type' => 'FBS',
                'warehouse_id' => '19294037531000',
            ],
            [
                'name' => 'Ярославская ФД',
                'type' => 'FBS',
                'warehouse_id' => '21076380483000',
            ],
            [
                'name' => 'Ярославская экспресс',
                'type' => 'Экспресс',
                'warehouse_id' => '23392814134000',
            ],
            [
                'name' => 'Ярославская Коник',
                'type' => 'FBS',
                'warehouse_id' => '1020000877283000',
            ],
        ];
        foreach ($data_items as $item) {
            DB::table('ozon_warehouses')->insert($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ozon_warehouses');
    }
};
