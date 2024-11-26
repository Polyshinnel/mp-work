<?php

namespace App\Console\Commands\Ozon;

use App\Http\Controllers\Ozon\OzonOrderController;
use Illuminate\Console\Command;

class UpdateOzonPacking extends Command
{
    private OzonOrderController $orderController;
    public function __construct(OzonOrderController $orderController)
    {
        parent::__construct();
        $this->orderController = $orderController;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon:update-ozon-packing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получает данные о заказах в Озон';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->orderController->addOrderToOrderList();
        return 1;
    }
}
