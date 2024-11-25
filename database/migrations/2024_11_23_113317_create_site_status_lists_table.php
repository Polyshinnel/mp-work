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
        Schema::create('site_status_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('site_status_id');
            $table->string('color')->nullable();
            $table->timestamps();
        });

        $data_items = [
            [
                'name' => 'Новый',
                'site_status_id' => 0,
                'color' => '#4671D5'
            ],
            [
                'name' => 'Резерв',
                'site_status_id' => 4,
                'color' => '#009999'
            ],
            [
                'name' => 'Принят',
                'site_status_id' => 1,
                'color' => '#1D7373'
            ],
            [
                'name' => 'КС',
                'site_status_id' => 9,
                'color' => '#33CCCC'
            ],
            [
                'name' => 'Доставлен',
                'site_status_id' => 2,
                'color' => '#5CCCCC'
            ],
            [
                'name' => 'Ожидает возврата',
                'site_status_id' => 96,
                'color' => '#6C8CD5'
            ],
            [
                'name' => 'Возвращен',
                'site_status_id' => 97,
                'color' => '#FFAA00'
            ],
        ];
        foreach ($data_items as $item) {
            DB::table('site_status_lists')->insert($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_status_lists');
    }
};
