<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private $successCount = 0;

    public function model(array $row)
    {
        // Skip rows without a name
        if (empty($row['name'])) {
            return null;
        }

        $this->successCount++;

        return new Product([
            'name' => $row['name'],
            'slug' => Str::slug($row['name']),
            'description' => $row['description'] ?? null,
            'image' => null,
            'price' => isset($row['price']) && $row['price'] !== null ? (float)$row['price'] : null,
            'discounted_price' => isset($row['discounted_price']) && $row['discounted_price'] !== null ? (float)$row['discounted_price'] : null,
            'stock' => isset($row['stock']) && $row['stock'] !== null ? (int)$row['stock'] : 0,
            'is_active' => 1,
            'upc' => $row['upc'] ?? null,
            'sku' => $row['sku'] ?? null,
            'asin' => $row['asin'] ?? null,
            'manufacturer' => $row['manufacturer'] ?? null,
            'moq' => isset($row['moq']) && $row['moq'] !== null ? (int)$row['moq'] : null,
            'meta_title' => $row['meta_title'] ?? null,
            'meta_description' => $row['meta_description'] ?? null,
            'meta_keywords' => $row['meta_keywords'] ?? null,
            'meta_tags' => $row['meta_tags'] ?? null,
            'page_schemas' => $row['page_schemas'] ?? null,
            'category_id' => isset($row['category_id']) && $row['category_id'] !== null ? (int)$row['category_id'] : null,
            'sub_category_id' => isset($row['sub_category_id']) && $row['sub_category_id'] !== null ? (int)$row['sub_category_id'] : null,
            'focus_keyword' => $row['focus_keyword'] ?? null,
            'type' => in_array($row['type'] ?? '', ['featured', 'best_seller']) ? $row['type'] : null,
        ]);
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }
}
