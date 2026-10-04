<?php

namespace App\Livewire\User;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Contact as ContactModel;

#[Layout('components.shop-layout')]
class Contact extends Component
{
    public $name = '';
    public $email = '';
    public $phone_number = '';
    public $services = '';
    public $message = '';
    
    public $successMessage = '';
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'required|string|max:20',
        'services' => 'nullable|string|max:255',
        'message' => 'required|string',
    ];
    
    protected $messages = [
        'name.required' => 'Please enter your name',
        'email.required' => 'Please enter your email address',
        'email.email' => 'Please enter a valid email address',
        'phone_number.required' => 'Please enter your phone number',
        'message.required' => 'Please enter your message',
        'message.min' => 'Message must be at least 10 characters',
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function save()
    {
        $this->validate();
        
        ContactModel ::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'services' => $this->services,
            'message' => $this->message,
        ]);
        
        $this->successMessage = 'Thank you! Your message has been sent successfully.';
        
        $this->resetForm();
        
        $this->dispatch('form-submitted');
    }
    
    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->services = '';
        $this->message = '';
    }
    
    public function render()
    {
        return view('livewire.user.contact');
    }
}
