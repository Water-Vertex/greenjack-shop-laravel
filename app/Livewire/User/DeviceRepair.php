<?php

namespace App\Livewire\User;

use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Category;
use App\Models\Problem;
use App\Models\RepairRequest;
use App\Models\Series;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Http;

#[Layout('components.user-layout')]
class DeviceRepair extends Component
{
    public $slug;
    public $category;

    // Repair calculator properties
    public $selectedCategory = null;
    public $selectedBrand = null;
    public $selectedModel = null;
    public $selectedProblems = [];
    public $brands = [];
    public $models = [];
    public $problems = [];
    public $seriesList;
    public $expandedSeries = null;
    public $modelSearch = '';
    public $problemSearch = '';
    public $currentStep = 1;

    // Categories for step 1
    public $categories = [];

    // Payment properties
    public $paymentMethod = 'stripe';
    public $isProcessing = false;
    public $showPaymentSuccess = false;
    public $paymentError = null;

    public $formData = [
        'category' => null,
        'brand' => null,
        'model' => null,
        'problems' => [],
        'total_price' => 0,
        'type' => null,
        'date' => null,
        'time' => null,
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'mobile' => '',
        'imei' => '',
        'address' => '',
        'zip_code' => '',
        'city' => '',
        'state' => '',
        'country' => '',
        'message' => '',
        'terms' => false,
    ];

    protected $rules = [
        'selectedCategory' => 'required',
        'selectedBrand' => 'required',
        'selectedModel' => 'required',
        'selectedProblems' => 'required|array|min:1',
        'formData.type' => 'required',
        'formData.date' => 'required|date',
        'formData.time' => 'required',
        'formData.first_name' => 'required|string|max:255',
        'formData.last_name' => 'required|string|max:255',
        'formData.email' => 'required|email|max:255',
        'formData.mobile' => 'required|string|max:20',
        'formData.imei' => 'nullable|string|max:50',
        'formData.address' => 'nullable|string',
        'formData.zip_code' => 'nullable|string|max:20',
        'formData.city' => 'nullable|string|max:100',
        'formData.state' => 'nullable|string|max:100',
        'formData.country' => 'nullable|string|max:100',
        'formData.message' => 'nullable|string',
        'formData.terms' => 'accepted',
        'paymentMethod' => 'required|in:stripe,paypal',
    ];

    protected $messages = [
        'selectedCategory.required' => 'Please select a category.',
        'selectedBrand.required' => 'Please select a brand.',
        'selectedModel.required' => 'Please select a model.',
        'selectedProblems.required' => 'Please select at least one problem.',
        'selectedProblems.min' => 'Please select at least one problem.',
        'formData.type.required' => 'Please select how you heard about us.',
        'formData.date.required' => 'Please select a preferred date.',
        'formData.time.required' => 'Please select a preferred time.',
        'formData.first_name.required' => 'Please enter your first name.',
        'formData.last_name.required' => 'Please enter your last name.',
        'formData.email.required' => 'Please enter your email address.',
        'formData.email.email' => 'Please enter a valid email address.',
        'formData.mobile.required' => 'Please enter your mobile number.',
        'formData.terms.accepted' => 'You must accept the terms and conditions.',
        'paymentMethod.required' => 'Please select a payment method.',
    ];

    public function mount($slug = null)
    {
        // Check for payment return FIRST before anything else


        // Normal mount flow
        $this->slug = $slug;
        $this->loadCategories();
        $this->loadCategory();

        if ($this->category) {
            $this->selectedCategory = $this->category->id;
            $this->formData['category'] = $this->category->id;
            $this->loadBrands();
            $this->currentStep = 2;
        }
    }

    public function verifyStripePayment($repairRequest)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve(request()->session_id);

            if ($session->payment_status === 'paid') {
                $repairRequest->update([
                    'payment_status' => 'completed',
                    'paid_at' => now(),
                    'status' => 'confirmed',
                    'stripe_payment_intent_id' => $session->payment_intent,
                ]);

                // Redirect to success page
                return redirect()->route('payment.success', ['id' => $repairRequest->id]);
            } else {
                // Payment not completed
                return redirect()->route('payment.cancelled', ['id' => $repairRequest->id]);
            }
        } catch (\Exception $e) {
            Log::error('Stripe verification error: ' . $e->getMessage());
            session()->flash('error', 'Payment verification failed. Please contact support.');
            return redirect()->route('device-repair', ['slug' => $this->slug ?? $repairRequest->category->slug]);
        }
    }

   public function verifyPayPalPayment($repairRequest)
{
    try {
        // Validate PayPal credentials are configured
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');

        if (empty($clientId) || empty($secret)) {
            throw new \Exception('PayPal credentials are not properly configured.');
        }

        // Get PayPal access token
        $response = Http::withBasicAuth($clientId, $secret)
            ->asForm()
            ->post(config('services.paypal.mode') === 'live'
                ? 'https://api-m.paypal.com/v1/oauth2/token'
                : 'https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get PayPal access token');
        }

        $accessToken = $response->json()['access_token'];

        // Capture the payment
        $captureResponse = Http::withToken($accessToken)->post(
            config('services.paypal.mode') === 'live'
                ? 'https://api-m.paypal.com/v2/checkout/orders/' . request()->token . '/capture'
                : 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' . request()->token . '/capture'
        );

        if ($captureResponse->successful() && $captureResponse->json()['status'] === 'COMPLETED') {
            $captureData = $captureResponse->json();

            $repairRequest->update([
                'payment_status' => 'completed',
                'paid_at' => now(),
                'status' => 'confirmed',
                'paypal_capture_id' => $captureData['purchase_units'][0]['payments']['captures'][0]['id'] ?? null,
            ]);

            // Redirect to success page
            return redirect()->route('payment.success', ['id' => $repairRequest->id]);
        } else {
            // Payment not completed
            return redirect()->route('payment.cancelled', ['id' => $repairRequest->id]);
        }
    } catch (\Exception $e) {
        Log::error('PayPal verification error: ' . $e->getMessage());
        session()->flash('error', 'Payment verification failed. Please contact support.');
        return redirect()->route('device-repair', ['slug' => $this->slug ?? $repairRequest->category->slug]);
    }
}

    public function loadCategories()
    {
        $this->categories = Category::where('is_repairing_category', 1)->get();
    }

    public function loadCategory()
    {
        if ($this->slug) {
            $this->category = Category::where('slug', $this->slug)->first();

            if (!$this->category) {
                return redirect()->route('home')->with('error', 'Category not found');
            }
        } else {
            return redirect()->route('home')->with('error', 'Category not specified');
        }
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->formData['category'] = $categoryId;

        $this->brands = Brand::whereHas('categories', function($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })->get();

        $this->resetBrandAndModel();
        $this->selectedProblems = [];
        $this->formData['problems'] = [];
        $this->formData['total_price'] = 0;
        $this->problems = collect();
        $this->seriesList = collect();
        $this->expandedSeries = null;

        $this->currentStep = 2;
    }

    public function loadBrands()
    {
        if ($this->selectedCategory) {
            $this->brands = Brand::whereHas('categories', function($query) {
                $query->where('categories.id', $this->selectedCategory);
            })->get();
        }
    }

    public function selectBrand($brandId)
    {
        $this->selectedBrand = $brandId;
        $this->formData['brand'] = $brandId;
        $this->loadSeriesAndModels();
        $this->selectedModel = null;
        $this->selectedProblems = [];
        $this->formData['model'] = null;
        $this->formData['problems'] = [];
        $this->formData['total_price'] = 0;
        $this->modelSearch = '';
        $this->problems = collect();
        $this->currentStep = 3;
    }

    public function loadSeriesAndModels()
    {
        $this->seriesList = Series::where('category_id', $this->selectedCategory)
            ->where('brand_id', $this->selectedBrand)
            ->with('models')
            ->get();

        $modelsInSeries = collect();
        foreach ($this->seriesList as $series) {
            foreach ($series->models as $model) {
                $modelsInSeries->push($model->id);
            }
        }

        $this->models = BrandModel::where('category_id', $this->selectedCategory)
            ->where('brand_id', $this->selectedBrand)
            ->whereNotIn('id', $modelsInSeries)
            ->orderBy('sorting_order', 'asc')
            ->get();
    }

    public function toggleSeries($seriesId)
    {
        if ($this->expandedSeries == $seriesId) {
            $this->expandedSeries = null;
        } else {
            $this->expandedSeries = $seriesId;
        }
    }

    public function selectModel($modelId)
    {
        $this->selectedModel = $modelId;
        $this->formData['model'] = $modelId;

        $this->problems = Problem::where('brand_model_id', $modelId)
            ->orderBy('name')
            ->get();

        $this->selectedProblems = [];
        $this->formData['problems'] = [];
        $this->formData['total_price'] = 0;
        $this->problemSearch = '';
        $this->currentStep = 4;
    }

    public function toggleProblem($problemId)
    {
        $problem = Problem::find($problemId);

        if (in_array($problemId, $this->selectedProblems)) {
            $this->selectedProblems = array_diff($this->selectedProblems, [$problemId]);
            unset($this->formData['problems'][$problemId]);
        } else {
            $this->selectedProblems[] = $problemId;
            $this->formData['problems'][$problemId] = [
                'id' => $problemId,
                'name' => $problem->name,
                'price' => $problem->discounted_price ?? $problem->price,
                'description' => $problem->description
            ];
        }

        $this->calculateTotalPrice();
    }

    public function removeProblem($problemId)
    {
        $this->selectedProblems = array_diff($this->selectedProblems, [$problemId]);
        unset($this->formData['problems'][$problemId]);
        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        $total = 0;
        foreach ($this->formData['problems'] as $problem) {
            $total += floatval($problem['price']);
        }
        $this->formData['total_price'] = $total;
    }

    public function resetBrandAndModel()
    {
        $this->selectedBrand = null;
        $this->selectedModel = null;
        $this->formData['brand'] = null;
        $this->formData['model'] = null;
        $this->models = collect();
        $this->seriesList = collect();
        $this->modelSearch = '';
        $this->expandedSeries = null;
    }

    public function goToStep($step)
    {
        if ($step == 2 && !$this->selectedCategory) {
            session()->flash('error', 'Please select a category first.');
            return;
        }
        if ($step == 3 && !$this->selectedBrand) {
            session()->flash('error', 'Please select a brand first.');
            return;
        }
        if ($step == 4 && !$this->selectedModel) {
            session()->flash('error', 'Please select a model first.');
            return;
        }
        if ($step == 5 && empty($this->selectedProblems)) {
            session()->flash('error', 'Please select at least one problem.');
            return;
        }

        if ($step <= $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    public function nextStep()
    {
        if ($this->currentStep == 1 && !$this->selectedCategory) {
            session()->flash('error', 'Please select a category.');
            return;
        }
        if ($this->currentStep == 2 && !$this->selectedBrand) {
            session()->flash('error', 'Please select a brand.');
            return;
        }
        if ($this->currentStep == 3 && !$this->selectedModel) {
            session()->flash('error', 'Please select a model.');
            return;
        }
        if ($this->currentStep == 4 && empty($this->selectedProblems)) {
            session()->flash('error', 'Please select at least one problem.');
            return;
        }
        if ($this->currentStep == 5 && empty($this->formData['type'])) {
            session()->flash('error', 'Please select how you heard about us.');
            return;
        }
        if ($this->currentStep == 6) {
            if (empty($this->formData['date'])) {
                session()->flash('error', 'Please select a preferred date.');
                return;
            }
            if (empty($this->formData['time'])) {
                session()->flash('error', 'Please select a preferred time.');
                return;
            }
        }

        if ($this->currentStep < 7) {
            $this->currentStep++;
            $this->dispatch('scrollToTop');
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('scrollToTop');
        }
    }

    public function submitForm()
{
    try {
        $this->validate();

        DB::beginTransaction();

        // Calculate tax (8%)
        $subtotal = $this->formData['total_price'];
        $tax = $subtotal * 0.08;
        $total = $subtotal + $tax;

        // Create repair request with pending payment status
        $repairRequest = RepairRequest::create([
            'category_id' => $this->selectedCategory,
            'brand_id' => $this->selectedBrand,
            'model_id' => $this->selectedModel,
            'problems' => json_encode(array_values($this->formData['problems'])),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_price' => $total,
            'service_type' => $this->formData['type'],
            'preferred_date' => $this->formData['date'],
            'preferred_time' => $this->formData['time'],
            'first_name' => $this->formData['first_name'],
            'last_name' => $this->formData['last_name'],
            'email' => $this->formData['email'],
            'mobile' => $this->formData['mobile'],
            'imei' => $this->formData['imei'],
            'address' => $this->formData['address'],
            'zip_code' => $this->formData['zip_code'],
            'city' => $this->formData['city'],
            'state' => $this->formData['state'],
            'country' => $this->formData['country'],
            'message' => $this->formData['message'],
            'status' => 'pending_payment',
            'payment_method' => $this->paymentMethod,
            'payment_status' => 'pending',
        ]);

        DB::commit();

        // Process payment based on selected method
        if ($this->paymentMethod === 'stripe') {
            return $this->processStripePayment($repairRequest);
        } else {
            $result = $this->processPayPalPayment($repairRequest);

            // If processPayPalPayment returns null (error occurred), stay on page
            if ($result === null) {
                return;
            }

            return $result;
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Repair Request Submission Error: ' . $e->getMessage());
        Log::error('Error Trace: ' . $e->getTraceAsString());
        $this->paymentError = 'Something went wrong: ' . $e->getMessage();
        $this->isProcessing = false;
        session()->flash('error', 'Failed to create repair request: ' . $e->getMessage());
    }
}

    public function processStripePayment($repairRequest)
    {
        try {
            $this->isProcessing = true;

            Stripe::setApiKey(config('services.stripe.secret'));

            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Device Repair Service',
                            'description' => 'Repair for ' . $this->category_name . ' ' . $this->brand_name . ' ' . $this->model_name,
                        ],
                        'unit_amount' => round($repairRequest->total_price * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['id' => $repairRequest->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancelled', ['id' => $repairRequest->id]),
                'customer_email' => $this->formData['email'],
                'metadata' => [
                    'repair_request_id' => $repairRequest->id,
                ],
            ]);

            $repairRequest->update([
                'stripe_session_id' => $session->id,
            ]);

            $this->isProcessing = false;

            return redirect()->away($session->url);

        } catch (\Exception $e) {
            Log::error('Stripe Payment Error: ' . $e->getMessage());
            $this->paymentError = 'Payment processing failed: ' . $e->getMessage();
            $this->isProcessing = false;
        }
    }

public function processPayPalPayment($repairRequest)
{
    try {
        $this->isProcessing = true;

        // Validate PayPal credentials are configured
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');

        if (empty($clientId) || empty($secret)) {
            throw new \Exception('PayPal credentials are not properly configured.');
        }

        // Get PayPal access token
        $response = Http::withBasicAuth($clientId, $secret)
            ->asForm()
            ->post(config('services.paypal.mode') === 'live'
                ? 'https://api-m.paypal.com/v1/oauth2/token'
                : 'https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get PayPal access token: ' . $response->body());
        }

        $accessToken = $response->json()['access_token'];

        // Create PayPal order
        $orderData = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => (string) $repairRequest->id,
                'description' => 'Device Repair Service - ' . $this->category_name . ' ' . $this->brand_name,
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($repairRequest->total_price, 2, '.', ''),
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => 'USD',
                            'value' => number_format($repairRequest->total_price, 2, '.', '')
                        ]
                    ]
                ],
                'items' => [[
                    'name' => 'Device Repair Service',
                    'description' => 'Repair for ' . $this->category_name . ' ' . $this->brand_name . ' ' . $this->model_name,
                    'unit_amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($repairRequest->total_price, 2, '.', '')
                    ],
                    'quantity' => '1',
                    'category' => 'DIGITAL_GOODS'
                ]],
                'custom_id' => (string) $repairRequest->id,
            ]],
            'application_context' => [
                'return_url' => route('payment.success', ['id' => $repairRequest->id]),
                'cancel_url' => route('payment.cancelled', ['id' => $repairRequest->id]),
                'brand_name' => 'Device Repair Service',
                'locale' => 'en-US',
                'landing_page' => 'LOGIN',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
            ]
        ];

        Log::info('Creating PayPal order', ['order_data' => $orderData]);

        $orderResponse = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'PayPal-Request-Id' => 'repair-' . $repairRequest->id . '-' . time(),
            ])
            ->post(
                config('services.paypal.mode') === 'live'
                    ? 'https://api-m.paypal.com/v2/checkout/orders'
                    : 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
                $orderData
            );

        Log::info('PayPal order response', [
            'successful' => $orderResponse->successful(),
            'status' => $orderResponse->status(),
            'body' => $orderResponse->body(),
        ]);

        if (!$orderResponse->successful()) {
            $errorBody = $orderResponse->json();
            $errorMessage = $errorBody['message'] ?? $orderResponse->body();
            throw new \Exception('Failed to create PayPal order: ' . $errorMessage);
        }

        $orderResponseData = $orderResponse->json();

        $repairRequest->update([
            'paypal_order_id' => $orderResponseData['id'],
        ]);

        // Find the approval URL
        $approvalUrl = null;
        foreach ($orderResponseData['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $approvalUrl = $link['href'];
                break;
            }
        }

        if (!$approvalUrl) {
            throw new \Exception('No approval URL found in PayPal response');
        }

        Log::info('PayPal approval URL', ['url' => $approvalUrl]);

        $this->isProcessing = false;

        // Redirect to PayPal approval URL
        return redirect()->away($approvalUrl);

    } catch (\Exception $e) {
        Log::error('PayPal Payment Error: ' . $e->getMessage());
        Log::error('PayPal Error Trace: ' . $e->getTraceAsString());

        $this->paymentError = 'Payment processing failed: ' . $e->getMessage();
        $this->isProcessing = false;
        session()->flash('error', 'PayPal payment failed: ' . $e->getMessage());
        return null;
    }
}

    public function resetForm()
    {
        $this->reset(['selectedCategory', 'selectedBrand', 'selectedModel', 'selectedProblems', 'currentStep', 'modelSearch', 'problemSearch']);
        $this->formData = [
            'category' => null,
            'brand' => null,
            'model' => null,
            'problems' => [],
            'total_price' => 0,
            'type' => null,
            'date' => null,
            'time' => null,
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'mobile' => '',
            'imei' => '',
            'address' => '',
            'zip_code' => '',
            'city' => '',
            'state' => '',
            'country' => '',
            'message' => '',
            'terms' => false,
        ];
        $this->loadCategories();
        $this->loadCategory();
        $this->loadBrands();
        $this->currentStep = 2;
        $this->dispatch('scrollToTop');
    }

    public function getCategoryNameProperty()
    {
        if ($this->selectedCategory) {
            $category = Category::find($this->selectedCategory);
            return $category ? $category->name : '';
        }
        return '';
    }

    public function getBrandNameProperty()
    {
        if ($this->selectedBrand) {
            $brand = Brand::find($this->selectedBrand);
            return $brand ? $brand->name : '';
        }
        return '';
    }

    public function getModelNameProperty()
    {
        if ($this->selectedModel) {
            $model = BrandModel::find($this->selectedModel);
            return $model ? $model->name : '';
        }
        return '';
    }

    public function getFilteredModelsProperty()
    {
        $models = $this->models instanceof Collection
            ? $this->models
            : collect($this->models);

        if (empty($this->modelSearch)) {
            return $models;
        }

        return $models->filter(function ($model) {
            return stripos($model->name ?? '', $this->modelSearch) !== false ||
                   stripos($model->model_number ?? '', $this->modelSearch) !== false;
        });
    }

    public function getFilteredProblemsProperty()
    {
        $problems = $this->problems instanceof Collection
            ? $this->problems
            : collect($this->problems);

        if (empty($this->problemSearch)) {
            return $problems;
        }

        return $problems->filter(function ($problem) {
            return stripos($problem->name ?? '', $this->problemSearch) !== false ||
                   stripos($problem->description ?? '', $this->problemSearch) !== false;
        });
    }

    public function getTotalPriceProperty()
    {
        return $this->formData['total_price'] ?? 0;
    }

    public function render()
    {
        // Get related categories (other device repair categories)
        $relatedCategories = Category::where('id', '!=', $this->category?->id)
            ->where('is_repairing_category', 1)
            ->get();

        return view('livewire.user.device-repair', [
            'relatedCategories' => $relatedCategories
        ]);
    }
}
