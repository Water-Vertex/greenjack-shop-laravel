<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class Search extends Component
{
    public $searchTerm = '';
    public $searchResults = [];
    public $showResults = false;
    public $isLoading = false;

    public function updatedSearchTerm($value)
    {
        try {
            $this->searchTerm = is_string($value) ? $value : '';
            $this->performSearch();
        } catch (\Throwable $e) {
            $this->logError('updatedSearchTerm', $e);
            $this->searchResults = [];
            $this->showResults = false;
        }
    }

    public function performSearch()
    {
        try {
            $term = trim((string) $this->searchTerm);

            if (strlen($term) < 2) {
                $this->searchResults = [];
                $this->showResults = false;
                $this->isLoading = false;
                return;
            }

            $this->isLoading = true;

            $this->searchResults = Product::with(['variations', 'category'])
                ->where('is_active', true)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                        ->orWhere('description', 'like', '%' . $term . '%')
                        ->orWhere('sku', 'like', '%' . $term . '%');
                    // ✅ short_description HATA DIYA
                })
                ->limit(10)
                ->get()
                ->map(function ($product) {
                    try {
                        $hasDiscount = $product->discounted_price && $product->discounted_price > 0;
                        $price = $hasDiscount ? (float) $product->discounted_price : (float) $product->price;
                        $comparePrice = $hasDiscount ? (float) $product->price : 0;

                        $variations = [];
                        if ($product->has_variations && $product->variations) {
                            foreach ($product->variations as $v) {
                                if (!empty($v->image)) {
                                    $variations[] = [
                                        'image' => $v->image,
                                        'price' => (float) ($v->price ?? 0),
                                    ];
                                }
                            }
                        }

                        return [
                            'id' => $product->id ?? null,
                            'name' => $product->name ?? 'Unknown Product',
                            'slug' => $product->slug ?? null,
                            'price' => $price,
                            'compare_price' => $comparePrice,
                            'image' => $product->image ?? null,
                            'has_variations' => (bool) $product->has_variations,
                            'variations' => $variations,
                            'category' => $product->category ? [
                                'name' => $product->category->name ?? null,
                                'slug' => $product->category->slug ?? null,
                            ] : null,
                        ];
                    } catch (\Throwable $inner) {
                        $this->logError('performSearch:mapItem', $inner, [
                            'product_id' => $product->id ?? null,
                        ]);
                        return null;
                    }
                })
                ->filter()
                ->values()
                ->toArray();

            // ✅ showResults term length pe based
            $this->showResults = true;

            Log::info('[Search] Term: "' . $term . '" | Results: ' . count($this->searchResults));

        } catch (\Throwable $e) {
            $this->logError('performSearch', $e, ['term' => $this->searchTerm]);
            $this->searchResults = [];
            $this->showResults = false;
        } finally {
            $this->isLoading = false;
        }
    }

    public function clearSearch()
    {
        try {
            $this->searchTerm = '';
            $this->searchResults = [];
            $this->showResults = false;
            $this->isLoading = false;
        } catch (\Throwable $e) {
            $this->logError('clearSearch', $e);
        }
    }

    public function render()
    {
        try {
            return view('livewire.user.search');
        } catch (\Throwable $e) {
            $this->logError('render', $e);
            return view('livewire.user.search');
        }
    }

    private function logError(string $method, \Throwable $e, array $extra = []): void
    {
        try {
            Log::error("[Search::{$method}] " . $e->getMessage(), array_merge([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()->fullUrl(),
                'term' => $this->searchTerm,
            ], $extra));
        } catch (\Throwable $inner) {}
    }
}
