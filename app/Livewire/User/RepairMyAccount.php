<?php

namespace App\Livewire\User;

use App\Models\Customer;
use App\Models\RepairRequest;
use App\Models\PrintingRequest;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

#[Layout('components.user-layout')]
class RepairMyAccount extends Component
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

    /** @var Collection|RepairRequest[] */
    public $repairRequests;

    /** @var Collection|PrintingRequest[] */
    public $printingRequests;

    /** @var Collection|RepairRequest[] */
    public $recentRepairRequests;

    public int $repairRequestCount = 0;
    public int $printingRequestCount = 0;
    public float $totalSpent = 0;

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
            return redirect()->route('repair.customer.login')->with('error', 'Please login to access your account.');
        }

        $this->customer = Customer::find(Session::get('customer_id'));

        if (!$this->customer) {
            Session::forget(['customer_id', 'customer_name', 'customer_email']);
            return redirect()->route('repair.customer.login')->with('error', 'Account not found. Please login again.');
        }

        // Initialize collections
        $this->repairRequests = new Collection();
        $this->printingRequests = new Collection();
        $this->recentRepairRequests = new Collection();

        // Load customer data
        $this->loadCustomerData();

        // Load repair requests
        $this->loadRepairRequests();

        // Load printing requests
        $this->loadPrintingRequests();
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

    public function loadRepairRequests(): void
    {
        if (!$this->customer) {
            $this->repairRequests = new Collection();
            $this->recentRepairRequests = new Collection();
            $this->repairRequestCount = 0;
            return;
        }

        // Get all repair requests for this customer by email
        $this->repairRequests = RepairRequest::where('email', $this->customer->email)
            ->with(['category', 'brand', 'model'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->repairRequestCount = $this->repairRequests->count();
        $this->recentRepairRequests = $this->repairRequests->take(5);
    }

    public function loadPrintingRequests(): void
    {
        if (!$this->customer) {
            $this->printingRequests = new Collection();
            $this->printingRequestCount = 0;
            return;
        }

        // Get all printing requests for this customer by email
        $this->printingRequests = PrintingRequest::where('email', $this->customer->email)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->printingRequestCount = $this->printingRequests->count();
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

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPrintingStatusBadgeClass($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function logout()
    {
        Session::forget(['customer_id', 'customer_name', 'customer_email']);
        session()->flash('success', 'You have been logged out successfully.');
        return redirect()->route('repair.customer.login');
    }

    public function render()
    {
        return view('livewire.user.repair-my-account', [
            'repairRequestCount' => $this->repairRequestCount,
            'printingRequestCount' => $this->printingRequestCount,
            'customer' => $this->customer,
            'repairRequests' => $this->repairRequests,
            'printingRequests' => $this->printingRequests,
            'recentRepairRequests' => $this->recentRepairRequests,
        ]);
    }
}
