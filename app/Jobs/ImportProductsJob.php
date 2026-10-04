<?php


// app/Jobs/ImportProductsJob.php
namespace App\Jobs;

use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportProductsJob implements ShouldQueue
{
    protected $filePath;
    protected $userId;

    public function __construct($filePath, $userId)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    public function handle()
    {
        Excel::import(new ProductsImport, storage_path('app/' . $this->filePath));
        
        // Notify user via notification or session
    }
}
