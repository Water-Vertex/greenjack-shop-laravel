<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

// ✅ Square SDK imports
use Square\SquareClient;
use Square\Environments;
use Square\Checkout\PaymentLinks\Requests\CreatePaymentLinkRequest;
use Square\Types\Order;
use Square\Types\OrderLineItem;
use Square\Types\Money;
use Square\Types\CheckoutOptions;
use Square\Types\PrePopulatedData;
use Square\Types\Address;
use Square\Types\Currency;
use Square\Exceptions\SquareApiException;
use Square\Types\AcceptedPaymentMethods;

// ✅ Stripe
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

// ✅ Mail
use App\Mail\OrderConfirmationMail;
use App\Mail\AdminOrderNotificationMail;

#[Layout('components.shop-layout')]
class Checkout extends Component
{
    public $currentStep = 1;
    public $totalSteps = 3;

    // Customer Info
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $phone = '';

    // Shipping
    public $address = '';
    public $apartment = '';
    public $city = '';
    public $state = '';
    public $zipCode = '';
    public $country = 'US';

    // Different Shipping
    public $differentShippingAddress = false;
    public $shippingAddress = '';
    public $shippingApartment = '';
    public $shippingCity = '';
    public $shippingState = '';
    public $shippingZipCode = '';
    public $shippingCountry = 'US';

    // ✅ Phone country code — default US
    public $phoneCountryCode = '+1';

    // Payment
    public $paymentMethod = 'square';

    // COD
    public $codNotes = '';

    // Notes
    public $orderNotes = '';

    // Cart
    public $cart = [];
    public $cartTotal = 0;
    public $cartCount = 0;
    public $shippingCost = 0;
    public $taxRate = 0.08;
    public $taxAmount = 0;
    public $grandTotal = 0;
    public $orderPlaced = false;
    public $orderNumber = '';
    public $isProcessing = false;

    // Login
    public $isLoggedIn = false;
    public $customer = null;
    public $loginEmail = '';
    public $loginPassword = '';
    public $showLoginForm = false;

    public $paymentError = null;

    // ✅ Expanded countries list
    public $countries = [
        'US' => 'United States',
        'CA' => 'Canada',
        'GB' => 'United Kingdom',
        'AU' => 'Australia',
        'PK' => 'Pakistan',
        'IN' => 'India',
        'AE' => 'United Arab Emirates',
        'SA' => 'Saudi Arabia',
        'DE' => 'Germany',
        'FR' => 'France',
        'IT' => 'Italy',
        'ES' => 'Spain',
        'NL' => 'Netherlands',
        'BR' => 'Brazil',
        'MX' => 'Mexico',
        'JP' => 'Japan',
        'CN' => 'China',
        'KR' => 'South Korea',
        'SG' => 'Singapore',
        'NZ' => 'New Zealand',
    ];

    protected $loginRules = [
        'loginEmail' => 'required|email|exists:customers,email',
        'loginPassword' => 'required|min:8',
    ];

    protected $loginMessages = [
        'loginEmail.required' => 'Please enter your email address.',
        'loginEmail.email' => 'Please enter a valid email address.',
        'loginEmail.exists' => 'This email is not registered.',
        'loginPassword.required' => 'Please enter your password.',
        'loginPassword.min' => 'Password must be at least 8 characters.',
    ];

    protected $rules = [
        'firstName' => 'required|string|max:100',
        'lastName' => 'required|string|max:100',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'phoneCountryCode' => 'required|string|max:6',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'zipCode' => 'required|string|max:20',
        'country' => 'required|string|max:2',
        'paymentMethod' => 'required|in:square,cod,stripe,paypal',
    ];

    protected $messages = [
        'firstName.required' => 'Please enter your first name.',
        'lastName.required' => 'Please enter your last name.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'phone.required' => 'Please enter your phone number.',
        'phoneCountryCode.required' => 'Please select your country code.',
        'address.required' => 'Please enter your address.',
        'city.required' => 'Please enter your city.',
        'state.required' => 'Please enter your state.',
        'zipCode.required' => 'Please enter your zip code.',
        'country.required' => 'Please select your country.',
    ];

    public function mount()
    {
        try {
            $this->loadCart();
            $this->handlePaymentCallback();
            $this->checkLoginStatus();
        } catch (\Throwable $e) {
            $this->logError('mount', $e);
            session()->flash('error', 'Could not load checkout. Please try again.');
        }
    }

    public function checkLoginStatus()
    {
        try {
            if (Session::has('customer_id')) {
                $this->isLoggedIn = true;
                $this->customer = Customer::find(Session::get('customer_id'));
                if ($this->customer) {
                    $this->autoFillCustomerData();
                }
            }
        } catch (\Throwable $e) {
            $this->logError('checkLoginStatus', $e);
        }
    }

    public function autoFillCustomerData()
    {
        try {
            if (!$this->customer) return;

            $this->firstName = $this->firstName ?: ($this->customer->firstname ?? '');
            $this->lastName  = $this->lastName  ?: ($this->customer->lastname ?? '');
            $this->email     = $this->email     ?: ($this->customer->email ?? '');
            $this->phone     = $this->phone     ?: ($this->customer->phone ?? '');
            $this->address   = $this->address   ?: ($this->customer->address ?? '');
            $this->city      = $this->city      ?: ($this->customer->city ?? '');
            $this->state     = $this->state     ?: ($this->customer->state ?? '');
            $this->zipCode   = $this->zipCode   ?: ($this->customer->postalcode ?? '');
            $this->country   = $this->country   ?: ($this->customer->country ?? 'US');
        } catch (\Throwable $e) {
            $this->logError('autoFillCustomerData', $e);
        }
    }

    public function login()
    {
        try {
            $this->validate($this->loginRules, $this->loginMessages);

            $customer = Customer::where('email', $this->loginEmail)->first();

            if (!$customer || !Hash::check($this->loginPassword, $customer->password)) {
                $this->addError('loginEmail', 'Invalid credentials. Please try again.');
                return;
            }

            if ($customer->status !== 'active') {
                $this->addError('loginEmail', 'Your account is not active. Please contact support.');
                return;
            }

            Session::put('customer_id', $customer->id);
            Session::put('customer_name', $customer->firstname . ' ' . $customer->lastname);
            Session::put('customer_email', $customer->email);

            $this->isLoggedIn = true;
            $this->customer = $customer;
            $this->showLoginForm = false;
            $this->loginEmail = '';
            $this->loginPassword = '';
            $this->autoFillCustomerData();

            session()->flash('success', 'Welcome back, ' . $customer->firstname . '! Your details have been filled.');
        } catch (\Throwable $e) {
            $this->logError('login', $e);
            session()->flash('error', 'Login failed. Please try again.');
        }
    }

    public function toggleLoginForm()
    {
        try {
            $this->showLoginForm = !$this->showLoginForm;
            if (!$this->showLoginForm) {
                $this->reset(['loginEmail', 'loginPassword']);
                $this->resetErrorBag();
            }
        } catch (\Throwable $e) {
            $this->logError('toggleLoginForm', $e);
        }
    }

    public function loadCart()
    {
        try {
            $cart = Session::get('cart', []);

            if (!is_array($cart)) {
                $cart = [];
                Session::put('cart', []);
            }

            $cleanCart = [];
            foreach ($cart as $key => $item) {
                if (!is_array($item)) continue;
                if (!isset($item['price'], $item['quantity'])) continue;

                $qty = (int) $item['quantity'];
                if ($qty <= 0) continue;

                $cleanCart[$key] = $item;
                $cleanCart[$key]['quantity'] = $qty;
                $cleanCart[$key]['price'] = (float) $item['price'];
            }

            if (count($cleanCart) !== count($cart)) {
                Session::put('cart', $cleanCart);
            }

            $this->cart = $cleanCart;
            $this->cartCount = (int) array_sum(array_column($cleanCart, 'quantity'));
            $this->cartTotal = (float) array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cleanCart));
            $this->calculateTotals();

        } catch (\Throwable $e) {
            $this->logError('loadCart', $e);
            $this->cart = [];
            $this->cartCount = 0;
            $this->cartTotal = 0;
            $this->shippingCost = 0;
            $this->taxAmount = 0;
            $this->grandTotal = 0;
        }
    }

    public function calculateTotals()
    {
        try {
            $subtotal = (float) $this->cartTotal;
            $this->shippingCost = $subtotal >= 50 ? 0 : 10;
            $this->taxAmount = ($subtotal + $this->shippingCost) * $this->taxRate;
            $this->grandTotal = $subtotal + $this->shippingCost + $this->taxAmount;
        } catch (\Throwable $e) {
            $this->logError('calculateTotals', $e);
            $this->shippingCost = 0;
            $this->taxAmount = 0;
            $this->grandTotal = $this->cartTotal;
        }
    }

    public function nextStep()
    {
        try {
            $this->validateCurrentStep();
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
            }
        } catch (\Throwable $e) {
            $this->logError('nextStep', $e);
        }
    }

    public function previousStep()
    {
        try {
            if ($this->currentStep > 1) {
                $this->currentStep--;
            }
        } catch (\Throwable $e) {
            $this->logError('previousStep', $e);
        }
    }

    public function validateCurrentStep()
    {
        $rules = [];

        switch ($this->currentStep) {
            case 1:
                $rules = [
                    'firstName' => 'required|string|max:100',
                    'lastName' => 'required|string|max:100',
                    'email' => 'required|email|max:255',
                    'phone' => 'required|string|max:20',
                    'phoneCountryCode' => 'required|string|max:6',
                    'address' => 'required|string|max:255',
                    'city' => 'required|string|max:100',
                    'state' => 'required|string|max:100',
                    'zipCode' => 'required|string|max:20',
                    'country' => 'required|string|max:2',
                ];

                if ($this->differentShippingAddress) {
                    $rules['shippingAddress'] = 'required|string|max:255';
                    $rules['shippingCity'] = 'required|string|max:100';
                    $rules['shippingState'] = 'required|string|max:100';
                    $rules['shippingZipCode'] = 'required|string|max:20';
                    $rules['shippingCountry'] = 'required|string|max:2';
                }
                break;
            case 2:
                $rules['paymentMethod'] = 'required|in:square,cod,stripe,paypal';
                break;
        }

        if (!empty($rules)) {
            $this->validate($rules);
        }
    }

    public function handlePaymentCallback()
    {
        try {
            if (request()->has('order_number') && Session::has('pending_checkout')) {
                $this->verifySquarePayment(request()->order_number);
                return;
            }

            if (Session::has('payment_callback')) {
                $callback = Session::get('payment_callback');
                Session::forget('payment_callback');

                if ($callback['type'] === 'square_success') {
                    $this->verifySquarePayment($callback['order_number']);
                } elseif ($callback['type'] === 'cancel') {
                    session()->flash('error', 'Payment was cancelled.');
                }
            }
        } catch (\Throwable $e) {
            $this->logError('handlePaymentCallback', $e);
        }
    }

    public function processPayment()
    {
        try {
            $this->validateCurrentStep();

            if (empty($this->cart)) {
                session()->flash('error', 'Your cart is empty.');
                return;
            }

            switch ($this->paymentMethod) {
                case 'cod':
                    return $this->placeOrder();
                case 'square':
                    return $this->processSquarePayment();
                case 'stripe':
                    return $this->processStripePayment();
                case 'paypal':
                    return $this->processPayPalPayment();
                default:
                    session()->flash('error', 'Invalid payment method selected.');
            }
        } catch (\Throwable $e) {
            $this->logError('processPayment', $e);
            session()->flash('error', 'Payment failed. Please try again.');
        }
    }

    private function getSquareClient()
    {
        return new SquareClient(
            token: config('services.square.access_token'),
            options: [
                'baseUrl' => config('services.square.environment') === 'production'
                    ? Environments::Production->value
                    : Environments::Sandbox->value,
            ],
        );
    }

    /**
     * ✅ Multi-country phone normalization
     * - US/Canada (+1): strict validation, fake number rejection
     * - Baaki countries: country code + digits E.164 format me
     */
    private function normalizePhoneForSquare(?string $phone, ?string $countryCode = '+1'): ?string
    {
        try {
            if (empty($phone)) return null;

            $rawPhone = trim($phone);
            $digits = preg_replace('/\D/', '', $rawPhone);
            if (empty($digits)) return null;

            // Agar user ne already + daala hai
            if (str_starts_with($rawPhone, '+') && strlen($digits) >= 10 && strlen($digits) <= 15) {
                return '+' . $digits;
            }

            $countryCode = $countryCode ?: '+1';

            // US/Canada strict validation
            if ($countryCode === '+1') {
                if (strlen($digits) === 10) {
                    $digits = '1' . $digits;
                }
                if (strlen($digits) === 11 && $digits[0] === '1') {
                    $areaCode = substr($digits, 1, 3);
                    $exchange = substr($digits, 4, 3);

                    // Reject fake 555-01XX numbers
                    if ($areaCode === '555' || $exchange === '555') {
                        return null;
                    }
                    return '+' . $digits;
                }
                return null;
            }

            // Baaki countries — basic E.164
            $cleanCode = preg_replace('/\D/', '', $countryCode);
            $fullNumber = $cleanCode . $digits;

            if (strlen($fullNumber) >= 10 && strlen($fullNumber) <= 15) {
                return '+' . $fullNumber;
            }

            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function processSquarePayment()
    {
        $this->isProcessing = true;
        $this->paymentError = null;

        try {
            $tempOrderNumber = 'TEMP-' . strtoupper(uniqid());
            $client = $this->getSquareClient();

            // Line items
            $lineItems = [];
            foreach ($this->cart as $item) {
                $lineItems[] = new OrderLineItem([
                    'name' => substr($item['name'] ?? 'Item', 0, 255),
                    'quantity' => (string) $item['quantity'],
                    'basePriceMoney' => new Money([
                        'amount' => (int) round($item['price'] * 100),
                        'currency' => Currency::Usd->value,
                    ]),
                ]);
            }

            // Shipping
            if ($this->shippingCost > 0) {
                $lineItems[] = new OrderLineItem([
                    'name' => 'Shipping',
                    'quantity' => '1',
                    'basePriceMoney' => new Money([
                        'amount' => (int) round($this->shippingCost * 100),
                        'currency' => Currency::Usd->value,
                    ]),
                ]);
            }

            // Tax
            if ($this->taxAmount > 0) {
                $lineItems[] = new OrderLineItem([
                    'name' => 'Tax',
                    'quantity' => '1',
                    'basePriceMoney' => new Money([
                        'amount' => (int) round($this->taxAmount * 100),
                        'currency' => Currency::Usd->value,
                    ]),
                ]);
            }

            Session::put('pending_checkout', $this->preparePendingData($tempOrderNumber));

            $redirectUrl = config('services.square.redirect_url') . '?order_number=' . $tempOrderNumber;

            // ✅ Pre-populated data with multi-country phone
            $prePopulatedData = [
                'buyerEmail' => $this->email,
                'buyerAddress' => new Address([
                    'addressLine1' => $this->address,
                    'locality' => $this->city,
                    'administrativeDistrictLevel1' => $this->state,
                    'postalCode' => $this->zipCode,
                    'country' => $this->country,
                ]),
            ];

            // ✅ Pass phone + country code
            $normalizedPhone = $this->normalizePhoneForSquare($this->phone, $this->phoneCountryCode);
            if ($normalizedPhone !== null) {
                $prePopulatedData['buyerPhoneNumber'] = $normalizedPhone;
            }

            $request = new CreatePaymentLinkRequest([
                'idempotencyKey' => 'sq-' . $tempOrderNumber,
                'description' => 'Order #' . $tempOrderNumber,
                'order' => new Order([
                    'locationId' => config('services.square.location_id'),
                    'lineItems' => $lineItems,
                ]),
                'checkoutOptions' => new CheckoutOptions([
                    'redirectUrl' => $redirectUrl,
                    'askForShippingAddress' => false,
                    'acceptedPaymentMethods' => new AcceptedPaymentMethods([
                        'afterpayClearpay' => true,
                    ]),
                ]),
                'prePopulatedData' => new PrePopulatedData($prePopulatedData),
            ]);

            $response = $client->checkout->paymentLinks->create($request);

            $paymentLink = $response->getPaymentLink();
            $checkoutUrl = $paymentLink->getUrl();

            Session::put('square_payment_link_id', $paymentLink->getId());
            Session::put('square_order_id', $paymentLink->getOrderId());
            Session::put('square_order_number', $tempOrderNumber);

            $this->isProcessing = false;
            return redirect()->away($checkoutUrl);

        } catch (SquareApiException $e) {
            Log::error('Square API Error: ' . $e->getMessage());
            try { Log::error('Status Code: ' . $e->getStatusCode()); } catch (\Throwable $inner) {}

            $errorDetail = 'Payment failed';
            try {
                foreach ($e->getErrors() as $error) {
                    $errorDetail .= ': ' . $error->getDetail();
                    break;
                }
            } catch (\Throwable $inner) {}

            $this->paymentError = $errorDetail;
            $this->isProcessing = false;
            session()->flash('error', $this->paymentError);

        } catch (\Throwable $e) {
            $this->logError('processSquarePayment', $e);
            $this->paymentError = 'Payment processing failed: ' . $e->getMessage();
            $this->isProcessing = false;
            session()->flash('error', $this->paymentError);
        }
    }

    private function preparePendingData($tempOrderNumber)
    {
        return [
            'order_number' => $tempOrderNumber,
            'customer_id' => $this->customer ? $this->customer->id : null,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            // ✅ Full phone with country code
            'phone' => $this->phoneCountryCode . $this->phone,
            'phone_country_code' => $this->phoneCountryCode,
            'phone_raw' => $this->phone,
            'address' => $this->address,
            'apartment' => $this->apartment,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'country' => $this->country,
            'different_shipping_address' => $this->differentShippingAddress,
            'shipping_address' => $this->shippingAddress,
            'shipping_apartment' => $this->shippingApartment,
            'shipping_city' => $this->shippingCity,
            'shipping_state' => $this->shippingState,
            'shipping_zip_code' => $this->shippingZipCode,
            'shipping_country' => $this->shippingCountry,
            'order_notes' => $this->orderNotes,
            'cart' => $this->cart,
            'cart_total' => $this->cartTotal,
            'shipping_cost' => $this->shippingCost,
            'tax_amount' => $this->taxAmount,
            'grand_total' => $this->grandTotal,
        ];
    }

    public function verifySquarePayment($tempOrderNumber)
    {
        $this->isProcessing = true;

        try {
            $pendingData = Session::get('pending_checkout');

            if (!$pendingData || $pendingData['order_number'] !== $tempOrderNumber) {
                $this->isProcessing = false;
                session()->flash('error', 'Order data not found.');
                return redirect()->route('checkout');
            }

            $client = $this->getSquareClient();
            $squareOrderId = Session::get('square_order_id');

            if (!$squareOrderId) {
                $this->isProcessing = false;
                session()->flash('error', 'Order ID not found.');
                return redirect()->route('checkout');
            }

            $response = $client->orders->get($squareOrderId);
            $sqOrder = $response->getOrder();

            $isPaid = false;
            $squarePaymentId = null;

            $tenders = $sqOrder->getTenders();
            if (!empty($tenders)) {
                foreach ($tenders as $tender) {
                    if ($tender->getPaymentId()) {
                        $squarePaymentId = $tender->getPaymentId();
                        $isPaid = true;
                        break;
                    }
                }
            }

            if (!$isPaid) {
                $this->isProcessing = false;
                session()->flash('error', 'Payment not completed. Please try again.');
                return redirect()->route('checkout');
            }

            $order = $this->createOrderFromPending($pendingData, 'square', [
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
            ]);

            Session::forget(['pending_checkout', 'square_order_id', 'square_payment_link_id', 'square_order_number', 'cart']);

            $this->orderNumber = $order->order_number;
            $this->orderPlaced = true;
            $this->currentStep = 3;
            $this->isProcessing = false;

            session()->flash('success', 'Payment successful! Your order has been placed.');
            $this->dispatch('cart-updated');

            $this->sendOrderEmails($order);

        } catch (SquareApiException $e) {
            Log::error('Square verification API error: ' . $e->getMessage());
            $this->isProcessing = false;
            session()->flash('error', 'Failed to verify payment: ' . $e->getMessage());
            return redirect()->route('checkout');

        } catch (\Throwable $e) {
            $this->logError('verifySquarePayment', $e);
            $this->isProcessing = false;
            session()->flash('error', 'Failed to verify payment: ' . $e->getMessage());
            return redirect()->route('checkout');
        }
    }

    public function processStripePayment()
    {
        $this->isProcessing = true;
        $this->paymentError = null;

        try {
            $tempOrderNumber = 'TEMP-' . strtoupper(uniqid());

            Stripe::setApiKey(config('services.stripe.secret'));

            $lineItems = [];
            foreach ($this->cart as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item['name'] ?? 'Item',
                            'description' => substr($item['description'] ?? $item['name'] ?? 'Item', 0, 255),
                        ],
                        'unit_amount' => (int) round($item['price'] * 100),
                    ],
                    'quantity' => (int) $item['quantity'],
                ];
            }

            if ($this->shippingCost > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Shipping Cost'],
                        'unit_amount' => (int) round($this->shippingCost * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            if ($this->taxAmount > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Tax'],
                        'unit_amount' => (int) round($this->taxAmount * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            Session::put('pending_checkout', $this->preparePendingData($tempOrderNumber));

            // ✅ Stripe session with conditional email
            $stripeData = [
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&order_number=' . $tempOrderNumber,
                'cancel_url' => route('checkout.cancel'),
                'metadata' => ['order_number' => $tempOrderNumber],
            ];

            if (!empty($this->email)) {
                $stripeData['customer_email'] = $this->email;
            }

            $session = StripeSession::create($stripeData);

            $this->isProcessing = false;
            return redirect()->away($session->url);

        } catch (\Throwable $e) {
            $this->logError('processStripePayment', $e);
            $this->paymentError = 'Payment processing failed: ' . $e->getMessage();
            $this->isProcessing = false;
            session()->flash('error', $this->paymentError);
        }
    }

    public function processPayPalPayment()
    {
        $this->isProcessing = true;
        $this->paymentError = null;

        try {
            $tempOrderNumber = 'TEMP-' . strtoupper(uniqid());

            $clientId = config('services.paypal.client_id');
            $secret = config('services.paypal.secret');

            if (empty($clientId) || empty($secret)) {
                throw new \Exception('PayPal credentials are not properly configured.');
            }

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

            Session::put('pending_checkout', $this->preparePendingData($tempOrderNumber));

            $items = [];
            foreach ($this->cart as $item) {
                if (empty($item['name']) || empty($item['price']) || empty($item['quantity']) || $item['quantity'] <= 0) {
                    continue;
                }

                $items[] = [
                    'name' => substr($item['name'], 0, 127),
                    'unit_amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format(max(0, (float)$item['price']), 2, '.', '')
                    ],
                    'quantity' => (string) max(1, (int)$item['quantity']),
                    'category' => 'DIGITAL_GOODS'
                ];
            }

            if (empty($items)) {
                throw new \Exception('No valid items in cart.');
            }

            $amountBreakdown = [
                'item_total' => [
                    'currency_code' => 'USD',
                    'value' => number_format($this->cartTotal, 2, '.', '')
                ]
            ];

            if ($this->shippingCost > 0) {
                $amountBreakdown['shipping'] = [
                    'currency_code' => 'USD',
                    'value' => number_format($this->shippingCost, 2, '.', '')
                ];
            }

            if ($this->taxAmount > 0) {
                $amountBreakdown['tax_total'] = [
                    'currency_code' => 'USD',
                    'value' => number_format($this->taxAmount, 2, '.', '')
                ];
            }

            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $tempOrderNumber,
                        'description' => 'Order #' . $tempOrderNumber,
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format($this->grandTotal, 2, '.', ''),
                            'breakdown' => $amountBreakdown
                        ],
                        'items' => $items,
                        'custom_id' => $tempOrderNumber,
                    ]
                ],
                'application_context' => [
                    'return_url' => route('paypal.success', ['order_number' => $tempOrderNumber]),
                    'cancel_url' => route('paypal.cancel'),
                    'brand_name' => 'Shop Checkout',
                    'locale' => 'en-US',
                    'landing_page' => 'LOGIN',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                ]
            ];

            Log::info('PayPal Order Request', ['orderData' => $orderData]);

            $orderResponse = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => 'order-' . $tempOrderNumber . '-' . time(),
                ])
                ->post(
                    config('services.paypal.mode') === 'live'
                        ? 'https://api-m.paypal.com/v2/checkout/orders'
                        : 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
                    $orderData
                );

            if (!$orderResponse->successful()) {
                throw new \Exception('Failed to create PayPal order: ' . $orderResponse->body());
            }

            $orderResponseData = $orderResponse->json();

            $approvalUrl = null;
            foreach (($orderResponseData['links'] ?? []) as $link) {
                if ($link['rel'] === 'approve') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }

            if (!$approvalUrl) {
                throw new \Exception('No approval URL found in PayPal response');
            }

            $this->isProcessing = false;
            return redirect()->away($approvalUrl);

        } catch (\Throwable $e) {
            $this->logError('processPayPalPayment', $e);
            $this->paymentError = 'Payment processing failed: ' . $e->getMessage();
            $this->isProcessing = false;
            session()->flash('error', $this->paymentError);
        }
    }

    public function verifyStripePayment($sessionId, $tempOrderNumber)
    {
        $this->isProcessing = true;

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                $this->isProcessing = false;
                session()->flash('error', 'Payment was not completed. Please try again.');
                return redirect()->route('checkout');
            }

            $pendingData = Session::get('pending_checkout');

            if (!$pendingData || $pendingData['order_number'] !== $tempOrderNumber) {
                $this->isProcessing = false;
                session()->flash('error', 'Order data not found.');
                return redirect()->route('checkout');
            }

            $order = $this->createOrderFromPending($pendingData, 'stripe', [
                'payment_status' => 'paid',
                'status' => 'processing',
                'stripe_session_id' => $sessionId,
                'stripe_payment_intent_id' => $session->payment_intent ?? null,
                'paid_at' => now(),
            ]);

            Session::forget(['pending_checkout', 'cart']);

            $this->orderNumber = $order->order_number;
            $this->orderPlaced = true;
            $this->currentStep = 3;
            $this->isProcessing = false;

            session()->flash('success', 'Payment successful! Your order has been placed.');
            $this->dispatch('cart-updated');

            $this->sendOrderEmails($order);

        } catch (\Throwable $e) {
            $this->logError('verifyStripePayment', $e);
            $this->isProcessing = false;
            session()->flash('error', 'Failed to verify payment: ' . $e->getMessage());
            return redirect()->route('checkout');
        }
    }

    public function verifyPayPalPayment($token)
    {
        $this->isProcessing = true;

        try {
            $clientId = config('services.paypal.client_id');
            $secret = config('services.paypal.secret');

            if (empty($clientId) || empty($secret)) {
                throw new \Exception('PayPal credentials are not properly configured.');
            }

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

            $captureResponse = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->send('POST',
                    config('services.paypal.mode') === 'live'
                        ? 'https://api-m.paypal.com/v2/checkout/orders/' . $token . '/capture'
                        : 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' . $token . '/capture',
                    ['body' => '{}']
                );

            if (!$captureResponse->successful()) {
                throw new \Exception('Failed to capture PayPal payment: ' . $captureResponse->body());
            }

            $captureData = $captureResponse->json();

            if (($captureData['status'] ?? '') !== 'COMPLETED') {
                $this->isProcessing = false;
                session()->flash('error', 'Payment was not completed. Please try again.');
                return redirect()->route('checkout');
            }

            $customId = $captureData['purchase_units'][0]['custom_id'] ?? null;
            $pendingData = Session::get('pending_checkout');

            if (!$pendingData || ($customId && $pendingData['order_number'] !== $customId)) {
                $this->isProcessing = false;
                session()->flash('error', 'Order data not found.');
                return redirect()->route('checkout');
            }

            $order = $this->createOrderFromPending($pendingData, 'paypal', [
                'payment_status' => 'paid',
                'status' => 'processing',
                'paypal_order_id' => $captureData['id'] ?? null,
                'paypal_capture_id' => $captureData['purchase_units'][0]['payments']['captures'][0]['id'] ?? null,
                'paid_at' => now(),
            ]);

            Session::forget(['pending_checkout', 'cart']);

            $this->orderNumber = $order->order_number;
            $this->orderPlaced = true;
            $this->currentStep = 3;
            $this->isProcessing = false;

            session()->flash('success', 'Payment successful! Your order has been placed.');
            $this->dispatch('cart-updated');

            $this->sendOrderEmails($order);

        } catch (\Throwable $e) {
            $this->logError('verifyPayPalPayment', $e);
            $this->isProcessing = false;
            session()->flash('error', 'Failed to verify payment: ' . $e->getMessage());
            return redirect()->route('checkout');
        }
    }

    private function createOrderFromPending(array $pendingData, string $paymentMethod, array $extra = [])
    {
        DB::beginTransaction();

        try {
            $order = \App\Models\Order::create(array_merge([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_id' => $pendingData['customer_id'] ?? null,
                'first_name' => $pendingData['first_name'] ?? '',
                'last_name' => $pendingData['last_name'] ?? '',
                'email' => $pendingData['email'] ?? '',
                'phone' => $pendingData['phone'] ?? '',
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'subtotal' => $pendingData['cart_total'] ?? 0,
                'shipping_cost' => $pendingData['shipping_cost'] ?? 0,
                'tax_amount' => $pendingData['tax_amount'] ?? 0,
                'grand_total' => $pendingData['grand_total'] ?? 0,
                'order_notes' => $pendingData['order_notes'] ?? '',
                'status' => 'pending',
            ], $extra));

            \App\Models\ShippingDetail::create([
                'order_id' => $order->id,
                'address' => $pendingData['address'] ?? '',
                'apartment' => $pendingData['apartment'] ?? '',
                'city' => $pendingData['city'] ?? '',
                'state' => $pendingData['state'] ?? '',
                'zip_code' => $pendingData['zip_code'] ?? '',
                'country' => $pendingData['country'] ?? '',
                'different_shipping' => $pendingData['different_shipping_address'] ?? false,
                'shipping_address' => $pendingData['shipping_address'] ?? '',
                'shipping_apartment' => $pendingData['shipping_apartment'] ?? '',
                'shipping_city' => $pendingData['shipping_city'] ?? '',
                'shipping_state' => $pendingData['shipping_state'] ?? '',
                'shipping_zip_code' => $pendingData['shipping_zip_code'] ?? '',
                'shipping_country' => $pendingData['shipping_country'] ?? '',
            ]);

            foreach (($pendingData['cart'] ?? []) as $item) {
                $orderItemData = [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? $item['id'] ?? null,
                    'product_name' => $item['name'] ?? 'Unknown',
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 0,
                    'subtotal' => ($item['price'] ?? 0) * ($item['quantity'] ?? 0),
                ];

                if (!empty($item['variation_id'])) {
                    $orderItemData['variation_id'] = $item['variation_id'];
                }
                if (isset($item['sku'])) {
                    $orderItemData['product_sku'] = $item['sku'];
                }
                if (isset($item['discounted_price']) && $item['discounted_price'] < ($item['price'] ?? 0)) {
                    $orderItemData['discounted_price'] = $item['discounted_price'];
                }
                if (!empty($item['variation_attributes'])) {
                    $orderItemData['attribute_string'] = $item['variation_attributes'];
                    $orderItemData['attributes'] = $item['variation_attributes'];
                }
                if (isset($item['image'])) {
                    $orderItemData['image'] = $item['image'];
                }

                \App\Models\OrderItem::create($orderItemData);
            }

            DB::commit();
            return $order;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function placeOrder()
    {
        try {
            $this->validateCurrentStep();

            if (empty($this->cart)) {
                session()->flash('error', 'Your cart is empty.');
                return;
            }

            $this->isProcessing = true;
            $pendingData = $this->preparePendingData('TEMP-' . strtoupper(uniqid()));

            $order = $this->createOrderFromPending($pendingData, 'cod', [
                'payment_status' => 'pending',
                'status' => 'pending',
                'order_notes' => $this->codNotes ?: $this->orderNotes,
            ]);

            Session::forget('cart');
            $this->dispatch('cart-updated');
            $this->orderPlaced = true;
            $this->currentStep = 3;
            $this->orderNumber = $order->order_number;

            session()->flash('success', 'Order placed successfully!');
            $this->dispatch('order-placed', orderNumber: $this->orderNumber);

            $this->sendOrderEmails($order);

        } catch (\Throwable $e) {
            $this->logError('placeOrder', $e);
            session()->flash('error', 'Failed to place order: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    private function sendOrderEmails($order)
    {
        try {
            if (!$order) return false;

            if (!empty($order->email)) {
                Mail::to($order->email)->send(new OrderConfirmationMail($order));
                Log::info('Customer order confirmation email sent', [
                    'order_id' => $order->id,
                    'email' => $order->email
                ]);
            }

            $adminEmail = config('mail.admin_email', 'order@greenjackshop.com');
            Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));

            Log::info('Admin notification email sent', [
                'order_id' => $order->id,
                'admin_email' => $adminEmail
            ]);

            return true;
        } catch (\Throwable $e) {
            $this->logError('sendOrderEmails', $e, ['order_id' => $order->id ?? null]);
            return false;
        }
    }

    public function continueShopping()
    {
        return redirect()->route('shop');
    }

    public function render()
    {
        try {
            if (empty($this->cart) && !$this->orderPlaced) {
                return redirect()->route('shop')->with('error', 'Your cart is empty.');
            }

            return view('livewire.user.checkout');
        } catch (\Throwable $e) {
            $this->logError('render', $e);
            return redirect()->route('shop')->with('error', 'Checkout could not load. Please try again.');
        }
    }

    private function logError(string $method, \Throwable $e, array $extra = []): void
    {
        try {
            Log::error("[Checkout::{$method}] " . $e->getMessage(), array_merge([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user' => auth()->id(),
                'url' => request()->fullUrl(),
            ], $extra));
        } catch (\Throwable $inner) {}
    }
}
