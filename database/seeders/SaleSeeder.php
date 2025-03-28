<?php

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Services\DataExtractionService;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    const API_PATH = 'sales';
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
            Sale::insert($data);
        }
    }
}
