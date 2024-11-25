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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('title');
            $table->string('block_title');
            $table->integer('parent_id')->default(0);
            $table->timestamps();
        });

        $data_items = [
            [
                'url' => '/',
                'title' => 'Главная',
                'block_title' => '',
                'parent_id' => 0
            ],
            [
                'url' => 'ozon-list',
                'title' => 'Озон',
                'block_title' => 'Ожидают сборки',
                'parent_id' => 0
            ],
            [
                'url' => 'ozon-list/awaiting-delivery',
                'title' => 'Озон',
                'block_title' => 'Ожидают отгрузки',
                'parent_id' => 0
            ],
            [
                'url' => 'ozon-list/delivery',
                'title' => 'Озон',
                'block_title' => 'Доставляются',
                'parent_id' => 0
            ],
            [
                'url' => 'ozon-list/arbitration',
                'title' => 'Озон',
                'block_title' => 'Спорные',
                'parent_id' => 0
            ],
            [
                'url' => 'ozon-list/delivered',
                'title' => 'Озон',
                'block_title' => 'Доставлены',
                'parent_id' => 0
            ],
            [
                'url' => 'ozon-list/canceled',
                'title' => 'Озон',
                'block_title' => 'Отменены',
                'parent_id' => 0
            ],
            [
                'url' => 'ozon-list/all',
                'title' => 'Озон',
                'block_title' => 'Все',
                'parent_id' => 0
            ],
            [
                'url' => 'settings',
                'title' => 'Настройки',
                'block_title' => 'Настройки',
                'parent_id' => 0
            ],
            [
                'url' => 'settings/common',
                'title' => 'Общие',
                'block_title' => 'Настройки сайта',
                'parent_id' => 9
            ],
            [
                'url' => 'settings/common/marks',
                'title' => 'Метки сайта',
                'block_title' => 'Список меток',
                'parent_id' => 10
            ],
            [
                'url' => 'settings/common/marks/add',
                'title' => 'Добавить метку',
                'block_title' => 'Добавление метки',
                'parent_id' => 11
            ],
            [
                'url' => 'settings/common/site-status',
                'title' => 'Статусы сайта',
                'block_title' => 'Список статусов сайта',
                'parent_id' => 10
            ],
            [
                'url' => 'settings/common/site-status/add',
                'title' => 'Добавить статус',
                'block_title' => 'Добавление статуса',
                'parent_id' => 13
            ],
            [
                'url' => 'settings/ozon-settings',
                'title' => 'Озон',
                'block_title' => 'Настройки Озон',
                'parent_id' => 9
            ],
            [
                'url' => 'settings/ozon-settings/warehouses',
                'title' => 'Список складов',
                'block_title' => 'Склады Озон',
                'parent_id' => 15
            ],
            [
                'url' => 'settings/ozon-settings/warehouses/add',
                'title' => 'Добавить склад',
                'block_title' => 'Добавление склада',
                'parent_id' => 16
            ],
            [
                'url' => 'settings/ozon-settings/statuses',
                'title' => 'Статусы озон',
                'block_title' => 'Список статусов Озон',
                'parent_id' => 15
            ],
            [
                'url' => 'settings/ozon-settings/statuses/add',
                'title' => 'Добавить статус',
                'block_title' => 'Добавление статуса',
                'parent_id' => 18
            ],
        ];
        foreach ($data_items as $item) {
            DB::table('pages')->insert($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
