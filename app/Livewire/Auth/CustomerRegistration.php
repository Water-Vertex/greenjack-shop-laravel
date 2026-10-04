<?php

namespace App\Livewire\Auth;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

#[Layout('components.shop-layout')]
class CustomerRegistration extends Component
{
    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $country = '';
    public $state = '';
    public $city = '';
    public $postalcode = '';
    public $password = '';
    public $password_confirmation = '';
    public $terms = false;

    public $step = 1; // 1: Personal Info, 2: Address, 3: Account Setup

    protected $rules = [
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'country' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'city' => 'required|string|max:100',
        'postalcode' => 'required|string|max:20',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required|min:8',
        'terms' => 'accepted',
    ];

    protected $messages = [
        'email.unique' => 'This email is already registered. Please login or use a different email.',
        'password.confirmed' => 'Passwords do not match.',
        'terms.accepted' => 'You must agree to the terms and conditions.',
    ];

    // This is a Livewire lifecycle hook - DO NOT call it directly
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function nextStep()
    {
        $rules = [];

        if ($this->step == 1) {
            $rules = [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email|max:255',
                'phone' => 'required|string|max:20',
            ];
        } elseif ($this->step == 2) {
            $rules = [
                'address' => 'required|string|max:500',
                'country' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'postalcode' => 'required|string|max:20',
            ];
        }

        $this->validate($rules);
        $this->step++;
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function register()
    {
        $this->validate();

        $customer = Customer::create([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'postalcode' => $this->postalcode,
            'password' => Hash::make($this->password),
            'status' => 'active',
        ]);

        // Store customer in session
        Session::put('customer_id', $customer->id);
        Session::put('customer_name', $customer->firstname . ' ' . $customer->lastname);

        session()->flash('success', 'Registration successful! Welcome to our store.');

        return redirect()->route('shop');
    }

    public function render()
    {
        return view('livewire.auth.customer-registration');
    }
}
