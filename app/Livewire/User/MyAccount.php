<?php

namespace App\Livewire\User;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Wishlist;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Collection;

#[Layout('components.shop-layout')]
class MyAccount extends Component
{
    // Profile Information
    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $country = '';
    public string $state = '';
    public string $city = '';
    public string $postalcode = '';

    // Password Change
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    // UI State
    public string $activeTab = 'dashboard';
    public bool $editMode = false;
    public bool $showPasswordForm = false;

    /** @var Customer|null */
    public $customer = null;

    /** @var Collection|Order[] */
    public $orders;

    /** @var Collection|Order[] */
    public $recentOrders;

    public int $orderCount = 0;
    public float $totalSpent = 0;
    public int $wishlistCount = 0;

    protected $listeners = ['wishlist-updated' => 'loadWishlistCount'];

    protected $rules = [
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'country' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'city' => 'required|string|max:100',
        'postalcode' => 'required|string|max:20',
    ];

    protected $passwordRules = [
        'current_password' => 'required|min:8',
        'new_password' => 'required|min:8|confirmed',
        'new_password_confirmation' => 'required|min:8',
    ];

    protected $messages = [
        'email.unique' => 'This email is already registered.',
        'current_password.required' => 'Please enter your current password.',
        'new_password.confirmed' => 'New passwords do not match.',
        'new_password.min' => 'Password must be at least 8 characters.',
    ];

    public function mount()
    {
        // Check if customer is logged in
        if (!Session::has('customer_id')) {
            return redirect()->route('customer.login')->with('error', 'Please login to access your account.');
        }

        $this->customer = Customer::find(Session::get('customer_id'));

        if (!$this->customer) {
            Session::forget(['customer_id', 'customer_name', 'customer_email']);
            return redirect()->route('customer.login')->with('error', 'Account not found. Please login again.');
        }

        // Initialize orders as empty collection
        $this->orders = new Collection();
        $this->recentOrders = new Collection();

        // Load customer data
        $this->loadCustomerData();

        // Load orders
        $this->loadOrders();

        // Load wishlist count
        $this->loadWishlistCount();
    }

    public function loadCustomerData(): void
    {
        if (!$this->customer) {
            return;
        }

        $this->firstname = $this->customer->firstname ?? '';
        $this->lastname = $this->customer->lastname ?? '';
        $this->email = $this->customer->email ?? '';
        $this->phone = $this->customer->phone ?? '';
        $this->address = $this->customer->address ?? '';
        $this->country = $this->customer->country ?? '';
        $this->state = $this->customer->state ?? '';
        $this->city = $this->customer->city ?? '';
        $this->postalcode = $this->customer->postalcode ?? '';
    }

    public function loadOrders(): void
    {
        if (!$this->customer) {
            $this->orders = new Collection();
            $this->recentOrders = new Collection();
            $this->orderCount = 0;
            $this->totalSpent = 0;
            return;
        }

        // Get all orders for this customer
        $this->orders = Order::where('customer_id', $this->customer->id)
            ->with(['items', 'shippingDetail'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->orderCount = $this->orders->count();
        $this->totalSpent = $this->orders->sum('grand_total');
        $this->recentOrders = $this->orders->take(5);
    }

    public function loadWishlistCount(): void
    {
        if (!$this->customer) {
            $this->wishlistCount = 0;
            return;
        }

        $this->wishlistCount = Wishlist::where('customer_id', $this->customer->id)
            ->whereHas('product', function ($q) {
                $q->where('is_active', true);
            })
            ->count();
    }

    public function updated($propertyName): void
    {
        if ($this->editMode && $this->customer) {
            $this->validateOnly($propertyName);
        }
    }

    public function toggleEdit(): void
    {
        if (!$this->customer) {
            return;
        }

        $this->editMode = !$this->editMode;
        if (!$this->editMode) {
            // Reset to original data if canceling edit
            $this->loadCustomerData();
        }
    }

    public function updateProfile(): void
    {
        if (!$this->customer) {
            session()->flash('error', 'Customer not found.');
            return;
        }

        $this->validate();

        // Check if email is being changed and if it's already taken
        if ($this->email !== $this->customer->email) {
            $existing = Customer::where('email', $this->email)->first();
            if ($existing && $existing->id !== $this->customer->id) {
                $this->addError('email', 'This email is already registered to another account.');
                return;
            }
        }

        $this->customer->update([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'postalcode' => $this->postalcode,
        ]);

        // Update session
        Session::put('customer_name', $this->firstname . ' ' . $this->lastname);
        Session::put('customer_email', $this->email);

        $this->editMode = false;
        session()->flash('success', 'Profile updated successfully!');
    }

    public function togglePasswordForm(): void
    {
        if (!$this->customer) {
            return;
        }

        $this->showPasswordForm = !$this->showPasswordForm;
        if (!$this->showPasswordForm) {
            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        }
    }

    public function updatePassword(): void
    {
        if (!$this->customer) {
            session()->flash('error', 'Customer not found.');
            return;
        }

        $this->validate($this->passwordRules);

        // Verify current password
        if (!Hash::check($this->current_password, $this->customer->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        // Update password
        $this->customer->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->showPasswordForm = false;
        session()->flash('success', 'Password updated successfully!');
    }

    public function logout()
    {
        Session::forget(['customer_id', 'customer_name', 'customer_email']);
        session()->flash('success', 'You have been logged out successfully.');
        return redirect()->route('customer.login');
    }

    public function render()
    {
        return view('livewire.user.my-account', [
            'orderCount' => $this->orderCount,
            'totalSpent' => $this->totalSpent,
            'customer' => $this->customer,
            'orders' => $this->orders,
            'recentOrders' => $this->recentOrders,
            'wishlistCount' => $this->wishlistCount,
        ]);
    }
}