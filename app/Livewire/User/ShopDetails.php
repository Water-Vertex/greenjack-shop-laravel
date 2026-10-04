<?php

namespace App\Livewire\User;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

#[Layout('components.shop-layout')]
class ShopDetails extends Component
{
    public $slug;
    public $products;
    public $brands;
    public $product;
    public $categoryId;
    public $quantity = 1;
    public $cartCount = 0;
    public $addingToCart = false;

    // Variation properties
    public $selectedVariationId = null;
    public $selectedAttributes = [];
    public $availableAttributes = [];
    public $variationPrice = null;
    public $variationStock = null;
    public $variationImage = null;
    public $variationSku = null;
    public $variationDiscount = null;

    // Track which attribute groups are "locked" (first attribute)
    public $lockedAttributeGroups = [];

    // ✅ Already in cart qty (for current variation/product)
    public $cartItemQty = 0;

    public function mount($slug = null)
    {
        try {
            $this->slug = $slug;

            if (!$this->loadData()) {
                return; // loadData already redirected
            }

            $this->getCartCount();
            $this->initializeVariation();

        } catch (\Throwable $e) {
            $this->logError('mount', $e);
            return redirect()->route('shop')->with('error', 'Could not load product. Please try again.');
        }
    }

    public function getCartCount()
    {
        try {
            $cart = Session::get('cart', []);
            if (!is_array($cart)) {
                $cart = [];
            }
            $this->cartCount = (int) array_sum(array_column($cart, 'quantity'));
        } catch (\Throwable $e) {
            $this->logError('getCartCount', $e);
            $this->cartCount = 0;
        }
    }

    public function toggleWishlist($productId)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                session()->flash('error', 'Invalid product.');
                return;
            }

            $product = Product::find($productId);
            if (!$product) {
                session()->flash('error', 'Product not found.');
                return;
            }

            $sessionId = Session::getId();
            $userId = Auth::id();

            $query = Wishlist::where('product_id', $productId);
            if ($userId) {
                $query->where('customer_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }

            $wishlistItem = $query->first();

            if ($wishlistItem) {
                $wishlistItem->delete();
                session()->flash('success', 'Removed from wishlist.');
                $this->dispatch('wishlist-removed', productId: $productId);
            } else {
                Wishlist::create([
                    'customer_id' => $userId,
                    'product_id' => $productId,
                    'session_id' => $userId ? null : $sessionId,
                ]);
                session()->flash('success', 'Added to wishlist!');
                $this->dispatch('wishlist-added', productId: $productId);
            }

            $this->dispatch('wishlist-updated');

        } catch (\Throwable $e) {
            $this->logError('toggleWishlist', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not update wishlist. Please try again.');
        }
    }

    /**
     * ✅ Load product data — returns true on success, false on failure (redirect already done)
     */
    public function loadData()
    {
        try {
            if (!$this->slug) {
                return redirect()->route('shop')->with('error', 'Product not specified');
            }

            $this->product = Product::with([
                'category',
                'productImages',
                'variations',
                'attributes.attribute',
            ])->where('slug', $this->slug)->first();

            if (!$this->product) {
                return redirect()->route('shop')->with('error', 'Product not found');
            }

            $this->categoryId = $this->product->category->id ?? null;

            // Brands (safe)
            try {
                $this->brands = Brand::with('categories')->get();
            } catch (\Throwable $e) {
                $this->logError('loadData:brands', $e);
                $this->brands = collect();
            }

            // Related products (safe)
            try {
                $this->products = Product::with(['category', 'variations'])
                    ->where('slug', '!=', $this->slug)
                    ->where('is_active', true)
                    ->where('category_id', $this->product->category_id)
                    ->orderBy('id', 'asc')
                    ->limit(12)
                    ->get();
            } catch (\Throwable $e) {
                $this->logError('loadData:relatedProducts', $e);
                $this->products = collect();
            }

            return true;

        } catch (\Throwable $e) {
            $this->logError('loadData', $e);
            return redirect()->route('shop')->with('error', 'Failed to load product.');
        }
    }

    // ============================================
    // VARIATION METHODS
    // ============================================

    public function initializeVariation()
    {
        try {
            if (!$this->product) {
                return;
            }

            if ($this->product->has_variations && $this->product->variations && $this->product->variations->isNotEmpty()) {
                $this->buildAvailableAttributes();
                $this->identifyLockedAttributeGroups();

                $firstVariation = $this->product->variations->first();
                $this->variationPrice = $firstVariation->price;
                $this->variationStock = $firstVariation->stock;
                $this->variationImage = $firstVariation->image;
                $this->variationSku = $firstVariation->sku;
                $this->variationDiscount = $firstVariation->discounted_price;

                $this->selectedAttributes = [];
                $this->selectedVariationId = null;
            } else {
                $this->variationPrice = $this->product->price;
                $this->variationStock = $this->product->stock;
                $this->variationImage = $this->product->image;
                $this->variationSku = $this->product->sku;
                $this->variationDiscount = $this->product->discounted_price;
            }

            // Update cart qty for current selection
            $this->refreshCartItemQty();

        } catch (\Throwable $e) {
            $this->logError('initializeVariation', $e);
        }
    }

    public function identifyLockedAttributeGroups()
    {
        try {
            $this->lockedAttributeGroups = [];
            if (empty($this->availableAttributes)) {
                return;
            }
            $firstGroup = $this->availableAttributes[0] ?? null;
            if ($firstGroup) {
                $this->lockedAttributeGroups[] = $firstGroup['attribute_id'];
            }
        } catch (\Throwable $e) {
            $this->logError('identifyLockedAttributeGroups', $e);
        }
    }

    public function buildAvailableAttributes()
    {
        try {
            if (!$this->product || !$this->product->has_variations) {
                return;
            }

            $attributeGroups = [];

            foreach ($this->product->variations as $variation) {
                $attrs = is_array($variation->attributes)
                    ? $variation->attributes
                    : json_decode($variation->attributes, true) ?? [];

                if (!is_array($attrs)) {
                    continue;
                }

                foreach ($attrs as $attrValueId) {
                    $attrValue = AttributeValue::with('attribute')->find($attrValueId);
                    if ($attrValue && $attrValue->attribute) {
                        $attrId = $attrValue->attribute_id;
                        if (!isset($attributeGroups[$attrId])) {
                            $attributeGroups[$attrId] = [
                                'attribute_id' => $attrId,
                                'attribute_name' => $attrValue->attribute->name,
                                'values' => [],
                            ];
                        }
                        $valueExists = false;
                        foreach ($attributeGroups[$attrId]['values'] as $existingValue) {
                            if ($existingValue['id'] == $attrValueId) {
                                $valueExists = true;
                                break;
                            }
                        }
                        if (!$valueExists) {
                            $attributeGroups[$attrId]['values'][] = [
                                'id' => $attrValueId,
                                'value' => $attrValue->value,
                                'color_code' => $attrValue->color_code,
                                'available' => true,
                            ];
                        }
                    }
                }
            }

            $this->availableAttributes = array_values($attributeGroups);

        } catch (\Throwable $e) {
            $this->logError('buildAvailableAttributes', $e);
            $this->availableAttributes = [];
        }
    }

    protected function buildTestSelection($attributeValueId)
    {
        try {
            $attrValue = AttributeValue::find($attributeValueId);
            if (!$attrValue) {
                return $this->selectedAttributes;
            }

            $targetAttributeId = $attrValue->attribute_id;

            $testSelection = array_filter($this->selectedAttributes, function ($id) use ($targetAttributeId) {
                $val = AttributeValue::find($id);
                return $val && $val->attribute_id != $targetAttributeId;
            });

            $testSelection[] = $attributeValueId;

            return array_values($testSelection);

        } catch (\Throwable $e) {
            $this->logError('buildTestSelection', $e);
            return $this->selectedAttributes;
        }
    }

    public function isAttributeValueAvailable($attributeValueId)
    {
        try {
            if (empty($this->selectedAttributes)) {
                return true;
            }

            $attrValue = AttributeValue::with('attribute')->find($attributeValueId);
            if ($attrValue && in_array($attrValue->attribute_id, $this->lockedAttributeGroups)) {
                return true;
            }

            $testSelection = $this->buildTestSelection($attributeValueId);
            return $this->isValidCombination($testSelection);

        } catch (\Throwable $e) {
            $this->logError('isAttributeValueAvailable', $e);
            return true;
        }
    }

    public function isValidCombination($selectedIds)
    {
        try {
            if (empty($selectedIds) || !$this->product) {
                return true;
            }

            foreach ($this->product->variations as $variation) {
                $attrs = is_array($variation->attributes)
                    ? $variation->attributes
                    : json_decode($variation->attributes, true) ?? [];

                if (!is_array($attrs)) {
                    continue;
                }

                $allMatch = true;
                foreach ($selectedIds as $selectedId) {
                    if (!in_array($selectedId, $attrs)) {
                        $allMatch = false;
                        break;
                    }
                }

                if ($allMatch) {
                    return true;
                }
            }

            return false;

        } catch (\Throwable $e) {
            $this->logError('isValidCombination', $e);
            return true;
        }
    }

    public function selectAttributeValue($attributeValueId)
    {
        try {
            $clickedAttrValue = AttributeValue::with('attribute')->find($attributeValueId);
            if (!$clickedAttrValue) {
                return;
            }

            $clickedAttributeId = $clickedAttrValue->attribute_id;
            $index = array_search($attributeValueId, $this->selectedAttributes);

            if ($index !== false) {
                array_splice($this->selectedAttributes, $index, 1);
            } else {
                $this->selectedAttributes = array_values(array_filter(
                    $this->selectedAttributes,
                    function ($id) use ($clickedAttributeId) {
                        $val = AttributeValue::find($id);
                        return !$val || $val->attribute_id != $clickedAttributeId;
                    }
                ));
                $this->selectedAttributes[] = $attributeValueId;
            }

            $this->findMatchingVariation();

            // ✅ Cart qty refresh after variation change
            $this->refreshCartItemQty();

            // Clamp quantity
            $newMaxStock = (int) ($this->variationStock ?? $this->product->stock ?? 0);
            if ($newMaxStock <= 0) {
                $this->quantity = 0;
            } elseif ($this->quantity > $newMaxStock) {
                $this->quantity = $newMaxStock;
            } elseif ($this->quantity < 1) {
                $this->quantity = 1;
            }

            $this->updateAvailability();

            if ($this->selectedVariationId) {
                $variation = $this->product->variations->find($this->selectedVariationId);
                if ($variation && $variation->image) {
                    $this->dispatch('update-main-image', imageUrl: asset('storage/' . $variation->image));
                }
            }

            $this->dispatch('attributes-updated');

        } catch (\Throwable $e) {
            $this->logError('selectAttributeValue', $e, ['attr_value_id' => $attributeValueId]);
        }
    }

    public function selectVariationFromGallery($variationId)
    {
        try {
            if (!$this->product || !$this->product->variations) {
                return;
            }

            $variation = $this->product->variations->find($variationId);
            if (!$variation) {
                return;
            }

            $attrs = is_array($variation->attributes)
                ? $variation->attributes
                : json_decode($variation->attributes, true) ?? [];

            $this->selectedAttributes = is_array($attrs) ? $attrs : [];

            $this->findMatchingVariation();
            $this->refreshCartItemQty();
            $this->updateAvailability();

            $this->dispatch('variation-updated');
            $this->dispatch('attributes-updated');

        } catch (\Throwable $e) {
            $this->logError('selectVariationFromGallery', $e, ['variation_id' => $variationId]);
        }
    }

    public function updateAvailability()
    {
        try {
            if (empty($this->availableAttributes)) {
                return;
            }

            foreach ($this->availableAttributes as $attrIndex => &$group) {
                $isLocked = in_array($group['attribute_id'], $this->lockedAttributeGroups);
                foreach ($group['values'] as &$value) {
                    if ($isLocked) {
                        $value['available'] = true;
                    } else {
                        $value['available'] = $this->isAttributeValueAvailable($value['id']);
                    }
                }
            }
        } catch (\Throwable $e) {
            $this->logError('updateAvailability', $e);
        }
    }

    public function findMatchingVariation()
    {
        try {
            if (!$this->product || !$this->product->variations) {
                return;
            }

            if (empty($this->selectedAttributes)) {
                $this->selectedVariationId = null;
                $firstVariation = $this->product->variations->first();
                if ($firstVariation) {
                    $this->variationPrice = $firstVariation->price;
                    $this->variationStock = $firstVariation->stock;
                    $this->variationImage = $firstVariation->image;
                    $this->variationSku = $firstVariation->sku;
                    $this->variationDiscount = $firstVariation->discounted_price;
                }
                $this->dispatch('update-main-image', imageUrl: asset('storage/' . $this->product->image));
                return;
            }

            if (!$this->isValidCombination($this->selectedAttributes)) {
                $this->selectedVariationId = null;
                $firstVariation = $this->product->variations->first();
                if ($firstVariation) {
                    $this->variationPrice = $firstVariation->price;
                    $this->variationStock = $firstVariation->stock;
                    $this->variationImage = $firstVariation->image;
                    $this->variationSku = $firstVariation->sku;
                    $this->variationDiscount = $firstVariation->discounted_price;
                }
                return;
            }

            $selectedCount = count($this->selectedAttributes);

            foreach ($this->product->variations as $variation) {
                $attrs = is_array($variation->attributes)
                    ? $variation->attributes
                    : json_decode($variation->attributes, true) ?? [];

                if (!is_array($attrs)) {
                    continue;
                }

                $matchCount = 0;
                foreach ($this->selectedAttributes as $selectedId) {
                    if (in_array($selectedId, $attrs)) {
                        $matchCount++;
                    }
                }

                if ($matchCount == $selectedCount) {
                    $this->selectedVariationId = $variation->id;
                    $this->updateVariationDetails($variation);

                    if ($variation->image) {
                        $this->dispatch('update-main-image', imageUrl: asset('storage/' . $variation->image));
                    }
                    return;
                }
            }

            $this->selectedVariationId = null;
            $firstVariation = $this->product->variations->first();
            if ($firstVariation) {
                $this->variationPrice = $firstVariation->price;
                $this->variationStock = $firstVariation->stock;
                $this->variationImage = $firstVariation->image;
                $this->variationSku = $firstVariation->sku;
                $this->variationDiscount = $firstVariation->discounted_price;
            }

        } catch (\Throwable $e) {
            $this->logError('findMatchingVariation', $e);
        }
    }

    public function updateVariationDetails($variation)
    {
        try {
            $this->variationPrice = $variation->price;
            $this->variationStock = $variation->stock;
            $this->variationImage = $variation->image;
            $this->variationSku = $variation->sku;
            $this->variationDiscount = $variation->discounted_price;

            if ($this->quantity > $this->variationStock) {
                $this->quantity = $this->variationStock > 0 ? 1 : 0;
            }

            if ($variation->image) {
                $this->dispatch('update-main-image', imageUrl: asset('storage/' . $variation->image));
            }

            $this->dispatch('variation-updated');

        } catch (\Throwable $e) {
            $this->logError('updateVariationDetails', $e);
        }
    }

    public function getSelectedAttributesString()
    {
        try {
            if (empty($this->selectedAttributes)) {
                return 'None selected';
            }

            $attrNames = [];
            foreach ($this->selectedAttributes as $attrValueId) {
                $attrValue = AttributeValue::with('attribute')->find($attrValueId);
                if ($attrValue && $attrValue->attribute) {
                    $attrNames[] = $attrValue->attribute->name . ': ' . $attrValue->value;
                }
            }
            return implode(', ', $attrNames);

        } catch (\Throwable $e) {
            $this->logError('getSelectedAttributesString', $e);
            return '';
        }
    }

    public function getVariationAttributeString()
    {
        return $this->getSelectedAttributesString() === 'None selected'
            ? ''
            : $this->getSelectedAttributesString();
    }

    public function getVariationImage()
    {
        try {
            if ($this->variationImage) {
                return asset('storage/' . $this->variationImage);
            }
            if ($this->product && $this->product->image) {
                return asset('storage/' . $this->product->image);
            }
            return 'https://placehold.co/400x400/e2e8f0/475569?text=No+Image';
        } catch (\Throwable $e) {
            $this->logError('getVariationImage', $e);
            return 'https://placehold.co/400x400/e2e8f0/475569?text=No+Image';
        }
    }

    public function getVariationPrice()
    {
        try {
            $price = $this->variationPrice ?? $this->product->price ?? 0;
            return number_format((float) $price, 2);
        } catch (\Throwable $e) {
            return '0.00';
        }
    }

    public function getVariationDiscountedPrice()
    {
        try {
            if ($this->variationDiscount && $this->variationDiscount < $this->variationPrice) {
                return number_format((float) $this->variationDiscount, 2);
            }
            if ($this->product && $this->product->discounted_price && $this->product->discounted_price < $this->product->price) {
                return number_format((float) $this->product->discounted_price, 2);
            }
            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function isAttributeSelected($attributeValueId)
    {
        try {
            return in_array($attributeValueId, $this->selectedAttributes);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * ✅ Calculate how many qty already in cart for current selection
     */
    public function refreshCartItemQty()
    {
        try {
            $cart = Session::get('cart', []);
            if (!is_array($cart)) {
                $cart = [];
            }

            $cartKey = $this->selectedVariationId ?? ($this->product->id ?? null);

            if ($cartKey && isset($cart[$cartKey])) {
                $this->cartItemQty = (int) ($cart[$cartKey]['quantity'] ?? 0);
            } else {
                $this->cartItemQty = 0;
            }
        } catch (\Throwable $e) {
            $this->logError('refreshCartItemQty', $e);
            $this->cartItemQty = 0;
        }
    }

    /**
     * ✅ Remaining qty allowed in current session (stock - already in cart)
     */
    public function getRemainingQty()
    {
        $stock = (int) ($this->variationStock ?? $this->product->stock ?? 0);
        return max(0, $stock - (int) $this->cartItemQty);
    }

    /**
     * ✅ If already-in-cart qty meets or exceeds stock, disable Add button
     */
    public function isMaxQtyInCart()
    {
        $stock = (int) ($this->variationStock ?? $this->product->stock ?? 0);
        return $stock > 0 && $this->cartItemQty >= $stock;
    }

    public function isAddToCartDisabled()
    {
        try {
            if (!$this->product) {
                return true;
            }

            if (!$this->product->has_variations) {
                return ($this->product->stock ?? 0) <= 0 || $this->isMaxQtyInCart();
            }

            if (!$this->selectedVariationId) {
                return true;
            }

            if (($this->variationStock ?? 0) <= 0) {
                return true;
            }

            return $this->isMaxQtyInCart();

        } catch (\Throwable $e) {
            $this->logError('isAddToCartDisabled', $e);
            return true;
        }
    }

    public function getAddToCartButtonText()
    {
        try {
            if (!$this->product) {
                return 'Unavailable';
            }

            if ($this->product->has_variations && !$this->selectedVariationId) {
                return 'Select Options';
            }

            if ($this->isMaxQtyInCart()) {
                return 'Already in Cart';
            }

            if ($this->isAddToCartDisabled()) {
                return 'Out of Stock';
            }

            return 'Add to Cart';

        } catch (\Throwable $e) {
            return 'Add to Cart';
        }
    }

    // ============================================
    // CART METHODS
    // ============================================

    public function increaseQuantity()
    {
        try {
            $stock = (int) ($this->variationStock ?? $this->product->stock ?? 0);
            $remaining = max(0, $stock - (int) $this->cartItemQty);

            if ($remaining <= 0) {
                return;
            }

            if ($this->quantity < $remaining) {
                $this->quantity++;
            } else {
                $this->quantity = $remaining;
            }
        } catch (\Throwable $e) {
            $this->logError('increaseQuantity', $e);
        }
    }

    public function decreaseQuantity()
    {
        try {
            if ($this->quantity > 1) {
                $this->quantity--;
            }
        } catch (\Throwable $e) {
            $this->logError('decreaseQuantity', $e);
        }
    }

    public function updatedQuantity($value)
    {
        try {
            $stock = (int) ($this->variationStock ?? $this->product->stock ?? 0);
            $remaining = max(0, $stock - (int) $this->cartItemQty);
            $value = (int) $value;

            if ($remaining <= 0) {
                $this->quantity = 0;
            } elseif ($value > $remaining) {
                $this->quantity = $remaining;
            } elseif ($value < 1) {
                $this->quantity = 1;
            } else {
                $this->quantity = $value;
            }
        } catch (\Throwable $e) {
            $this->logError('updatedQuantity', $e);
            $this->quantity = 1;
        }
    }

    public function addToCart()
    {
        try {
            if (!$this->product) {
                session()->flash('error', 'Product not available.');
                return;
            }

            if ($this->product->has_variations && !$this->selectedVariationId) {
                session()->flash('error', 'Please select all product options first.');
                $this->dispatch('show-error', message: 'Please select all product options first.');
                return;
            }

            $stock = (int) ($this->variationStock ?? $this->product->stock ?? 0);
            if ($stock <= 0) {
                session()->flash('error', 'This product is currently out of stock.');
                $this->dispatch('show-error', message: 'This product is currently out of stock.');
                return;
            }

            $this->addingToCart = true;

            // ✅ Refresh already-in-cart qty
            $this->refreshCartItemQty();

            $remaining = max(0, $stock - (int) $this->cartItemQty);
            if ($remaining <= 0) {
                session()->flash('error', 'Maximum available quantity already in cart.');
                $this->addingToCart = false;
                return;
            }

            if ($this->quantity <= 0 || $this->quantity > $remaining) {
                session()->flash('error', 'Only ' . $remaining . ' more item(s) can be added.');
                $this->addingToCart = false;
                return;
            }

            $cart = Session::get('cart', []);
            if (!is_array($cart)) {
                $cart = [];
            }

            $itemId = $this->selectedVariationId ?? $this->product->id;
            $price = $this->variationPrice ?? $this->product->price;
            $name = $this->product->name;
            $image = $this->variationImage ?? $this->product->image;
            $sku = $this->variationSku ?? $this->product->sku;

            $cartKey = $itemId;

            if (isset($cart[$cartKey])) {
                $newQuantity = $cart[$cartKey]['quantity'] + $this->quantity;
                if ($newQuantity > $stock) {
                    session()->flash('error', 'Cannot add more than available stock.');
                    $this->addingToCart = false;
                    return;
                }
                $cart[$cartKey]['quantity'] = $newQuantity;
                session()->flash('success', 'Cart updated successfully!');
            } else {
                $cart[$cartKey] = [
                    'id' => $itemId,
                    'product_id' => $this->product->id,
                    'variation_id' => $this->selectedVariationId,
                    'name' => $name,
                    'slug' => $this->product->slug,
                    'image' => $image,
                    'price' => $price,
                    'quantity' => $this->quantity,
                    'stock' => $stock,
                    'sku' => $sku,
                    'variation_attributes' => $this->getVariationAttributeString(),
                ];
                session()->flash('success', 'Product added to cart successfully!');
            }

            Session::put('cart', $cart);
            $this->quantity = 1;
            $this->getCartCount();
            $this->refreshCartItemQty();
            $this->dispatch('cart-updated');
            $this->addingToCart = false;

        } catch (\Throwable $e) {
            $this->logError('addToCart', $e);
            session()->flash('error', 'Could not add to cart. Please try again.');
            $this->addingToCart = false;
        }
    }

    public function addRelatedToCart($productId, $quantity = 1)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                session()->flash('error', 'Invalid product.');
                return;
            }

            $quantity = max(1, (int) $quantity);
            $product = Product::find($productId);

            if (!$product) {
                session()->flash('error', 'Product not found.');
                return;
            }

            if (($product->stock ?? 0) < $quantity) {
                session()->flash('error', 'Product not available in requested quantity.');
                return;
            }

            $cart = Session::get('cart', []);
            if (!is_array($cart)) {
                $cart = [];
            }

            $price = ($product->discounted_price && $product->discounted_price > 0)
                ? $product->discounted_price
                : $product->price;

            $existingQty = isset($cart[$productId]) ? (int) $cart[$productId]['quantity'] : 0;
            $remaining = max(0, (int) $product->stock - $existingQty);

            if ($remaining <= 0) {
                session()->flash('error', 'Maximum available quantity already in cart.');
                return;
            }

            if ($quantity > $remaining) {
                $quantity = $remaining;
            }

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'id' => $productId,
                    'product_id' => $productId,
                    'variation_id' => null,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image,
                    'price' => $price,
                    'quantity' => $quantity,
                    'stock' => $product->stock,
                    'sku' => $product->sku,
                    'variation_attributes' => '',
                ];
            }

            Session::put('cart', $cart);
            $this->dispatch('cart-updated');
            session()->flash('success', $product->name . ' added to cart!');

        } catch (\Throwable $e) {
            $this->logError('addRelatedToCart', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not add to cart. Please try again.');
        }
    }

    public function refreshData()
    {
        try {
            $this->loadData();
            $this->initializeVariation();
            $this->buildAvailableAttributes();
            $this->identifyLockedAttributeGroups();
            $this->dispatch('data-refreshed');
        } catch (\Throwable $e) {
            $this->logError('refreshData', $e);
        }
    }

    public function render()
    {
        try {
            return view('livewire.user.shop-details', [
                'product' => $this->product,
                'products' => $this->products ?? collect(),
                'brands' => $this->brands ?? collect(),
                'categoryId' => $this->categoryId,
                'variationPrice' => $this->variationPrice,
                'variationStock' => $this->variationStock,
                'variationImage' => $this->variationImage,
                'variationSku' => $this->variationSku,
                'availableAttributes' => $this->availableAttributes,
                'selectedAttributes' => $this->selectedAttributes,
                'selectedVariationId' => $this->selectedVariationId,
                'lockedAttributeGroups' => $this->lockedAttributeGroups,
                'isAddToCartDisabled' => $this->isAddToCartDisabled(),
                'addToCartButtonText' => $this->getAddToCartButtonText(),
                'cartItemQty' => $this->cartItemQty,
                'remainingQty' => $this->getRemainingQty(),
                'isMaxQtyInCart' => $this->isMaxQtyInCart(),
            ]);
        } catch (\Throwable $e) {
            $this->logError('render', $e);
            return view('livewire.user.shop-details', [
                'product' => null,
                'products' => collect(),
                'brands' => collect(),
                'categoryId' => null,
                'variationPrice' => null,
                'variationStock' => 0,
                'variationImage' => null,
                'variationSku' => null,
                'availableAttributes' => [],
                'selectedAttributes' => [],
                'selectedVariationId' => null,
                'lockedAttributeGroups' => [],
                'isAddToCartDisabled' => true,
                'addToCartButtonText' => 'Unavailable',
                'cartItemQty' => 0,
                'remainingQty' => 0,
                'isMaxQtyInCart' => true,
            ]);
        }
    }

    private function logError(string $method, \Throwable $e, array $extra = []): void
    {
        try {
            Log::error("[ShopDetails::{$method}] " . $e->getMessage(), array_merge([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user' => Auth::id(),
                'url' => request()->fullUrl(),
                'slug' => $this->slug,
            ], $extra));
        } catch (\Throwable $inner) {
            // silent
        }
    }
}
