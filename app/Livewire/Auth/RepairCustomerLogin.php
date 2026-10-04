<?php

namespace App\Livewire\Auth;

use App\Models\Customer;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;

#[Layout('components.user-layout')]

class RepairCustomerLogin extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $showPassword = false;

    protected $rules = [
        'email' => 'required|email|exists:customers,email',
        'password' => 'required|min:8',
    ];

    protected $messages = [
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'email.exists' => 'This email is not registered. Please create an account.',
        'password.required' => 'Please enter your password.',
        'password.min' => 'Password must be at least 8 characters.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        $this->validate();

        $customer = Customer::where('email', $this->email)->first();

        if (!$customer || !Hash::check($this->password, $customer->password)) {
            $this->addError('email', 'Invalid credentials. Please try again.');
            return;
        }

        if ($customer->status !== 'active') {
            $this->addError('email', 'Your account is not active. Please contact support.');
            return;
        }

        // Store customer in session
        Session::put('customer_id', $customer->id);
        Session::put('customer_name', $customer->firstname . ' ' . $customer->lastname);
        Session::put('customer_email', $customer->email);

        session()->flash('success', 'Welcome back, ' . $customer->firstname . '!');

        // Redirect to dashboard or intended page
        return redirect()->intended(route('home'));
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.auth.repair-customer-login');
    }
}
