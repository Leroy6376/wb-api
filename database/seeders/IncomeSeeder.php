<?php

namespace Database\Seeders;


//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Income;
use Services\DataExtractionService;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    const API_PATH = 'incomes';
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
            Income::insert($data);
        }
    }
}
