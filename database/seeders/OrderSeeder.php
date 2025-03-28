<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Services\DataExtractionService;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    const API_PATH = 'orders';
    private $dataExtractionService;

    public function __construct(DataExtractionService $dataExtractionService)
    {
        $this->dataExtractionService = $dataExtractionService;
    }

    public function run(): void
    {
        $queryParams = [
            'dateTo' => now()->format("Y-m-d")
        ];
        $page = 1;
        $data = null;
        while(($data = $this->dataExtractionService->getData(self::API_PATH, $queryParams, $page++)) != null) {
            Order::insert($data);
        }
    }
}
