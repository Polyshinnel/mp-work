<?php

namespace App\Console\Commands\Ozon;

use App\Jobs\UpdateOzonOrders;
use Illuminate\Console\Command;

class UpdateOzonOrderSiteInfo extends Command
{
    private UpdateOzonOrders $orderJob;

    public function __construct(UpdateOzonOrders $orderJob)
    {
        parent::__construct();
        $this->orderJob = $orderJob;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon:update-ozon-order-site-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет данные о статусе и изменении метки на сайте';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->orderJob->updateSiteStatus();
        return 0;
    }
}
