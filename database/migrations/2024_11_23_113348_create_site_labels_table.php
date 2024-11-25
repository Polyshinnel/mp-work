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
        Schema::create('site_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('site_label_id');
            $table->string('color')->nullable();
            $table->timestamps();
        });

        $data_items = [
            [
                'name' => 'Cклад - сборка',
                'site_label_id' => 24,
                'color' => '#e0b0ff',
            ],
            [
                'name' => 'Склад - готово',
                'site_label_id' => 16,
                'color' => '#AE93BF',
            ],
            [
                'name' => 'Склад - упаковка',
                'site_label_id' => 105,
                'color' => '#E8C4FF',
            ],
        ];
        foreach ($data_items as $item) {
            DB::table('site_labels')->insert($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_labels');
    }
};
