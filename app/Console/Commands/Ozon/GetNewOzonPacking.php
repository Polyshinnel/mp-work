<?php

namespace App\Console\Commands\Ozon;

use App\Jobs\AddNewOzonOrders;
use Illuminate\Console\Command;

class GetNewOzonPacking extends Command
{
    private AddNewOzonOrders $orderController;
    public function __construct(AddNewOzonOrders $orderController)
    {
        parent::__construct();
        $this->orderController = $orderController;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon:get-new-ozon-packing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получает данные о новых заказах в Озон';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->orderController->addOrderToOrderList();
        return 0;
    }
}
