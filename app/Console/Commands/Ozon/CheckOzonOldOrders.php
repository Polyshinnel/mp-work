<?php

namespace App\Console\Commands\Ozon;

use App\Jobs\CheckOldOzonOrders;
use Illuminate\Console\Command;

class CheckOzonOldOrders extends Command
{
    private CheckOldOzonOrders $checkOldOzonOrders;

    public function __construct(CheckOldOzonOrders $checkOldOzonOrders)
    {
        parent::__construct();
        $this->checkOldOzonOrders = $checkOldOzonOrders;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon:check-ozon-old-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет старые заказы которые не меняются больше 2х дней';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->checkOldOzonOrders->updateOldOzonOrders();
        return 0;
    }
}
