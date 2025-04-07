<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearLabelFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-label-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folders = ['ozon-labels', 'yandex-labels'];

        foreach ($folders as $folder) {
            // Проверяем существование папки
            if (Storage::disk('public')->exists($folder)) {
                // Получаем все файлы в папке
                $files = Storage::disk('public')->allFiles($folder);

                if (count($files) > 0) {
                    // Удаляем каждый файл
                    Storage::disk('public')->delete($files);
                    $this->info("Deleted ".count($files)." files from {$folder} folder.");
                } else {
                    $this->info("No files found in {$folder} folder.");
                }
            } else {
                $this->warn("Folder {$folder} does not exist.");
            }
        }

        $this->info('All label folders cleared successfully!');
    }
}
