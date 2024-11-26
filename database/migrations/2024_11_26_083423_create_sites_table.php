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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('db_name');
            $table->string('prefix');
            $table->string('host');
            $table->timestamps();
        });

        $data_items = [
            [
                'db_name' => 'liberty-jones.ru',
                'prefix' => 'lib',
                'host' => 'https:/liberty-jones.ru/'
            ],
            [
                'db_name' => 'smart-solution.me',
                'prefix' => 'sma',
                'host' => 'https://smart-solution.me/'
            ],
            [
                'db_name' => 'karmakiss',
                'prefix' => 'kks',
                'host' => 'https://karmakiss.ru/'
            ],
            [
                'db_name' => 'djecoshop',
                'prefix' => 'dj',
                'host' => 'https://djecoshop.ru/'
            ],
            [
                'db_name' => 'schleichtoys',
                'prefix' => 'st',
                'host' => 'https://schleichtoys.ru/'
            ],
            [
                'db_name' => 'bergensons.ru',
                'prefix' => 'ber',
                'host' => 'https://bergensons.ru/'
            ],
            [
                'db_name' => 'umbrashop',
                'prefix' => 'us',
                'host' => 'https://umbrashop.ru/'
            ],
            [
                'db_name' => 'monbento',
                'prefix' => 'mb',
                'host' => 'https://monbento.me/'
            ],
            [
                'db_name' => 'paolareinas',
                'prefix' => 'pr',
                'host' => 'https://paolareinas.ru/'
            ],
            [
                'db_name' => 'paposhop',
                'prefix' => 'pps',
                'host' => 'https://paposhop.ru/'
            ],
            [
                'db_name' => 'vtechtoys',
                'prefix' => 'vt',
                'host' => 'https://vtechtoys.ru/'
            ],
            [
                'db_name' => 'guzzini.me',
                'prefix' => 'guz',
                'host' => 'https://guzzini.me/'
            ],
            [
                'db_name' => 'likelunch',
                'prefix' => 'like',
                'host' => 'https://likelunch.ru/'
            ],
            [
                'db_name' => 'kidkong',
                'prefix' => 'kk',
                'host' => 'https://kidkong.ru/'
            ],
            [
                'db_name' => 'masoncash',
                'prefix' => 'msc',
                'host' => 'https://masoncash.me/'
            ],
            [
                'db_name' => 'typhoon-store',
                'prefix' => 'tps',
                'host' => 'https://typhoonstore.ru/'
            ],
        ];
        foreach ($data_items as $item) {
            DB::table('sites')->insert($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
