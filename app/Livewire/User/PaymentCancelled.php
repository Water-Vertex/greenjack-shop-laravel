<?php

namespace App\Livewire\User;

use App\Models\RepairRequest;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.user-layout')]
class PaymentCancelled extends Component
{
    public $repairRequest;
    public $message;

    public function mount($id = null)
    {
        if (!$id) {
            return redirect()->route('home');
        }

        $this->repairRequest = RepairRequest::with(['category', 'brand', 'model'])
            ->find($id);

        if (!$this->repairRequest) {
            return redirect()->route('home')->with('error', 'Repair request not found.');
        }

        $this->message = 'Your payment was cancelled. No charges were made to your account.';
    }

    public function retryPayment()
    {
        // Redirect back to the repair form with the same device
        return redirect()->route('device-repair', [
            'slug' => $this->repairRequest->category->slug,
            'step' => 7
        ]);
    }

    public function render()
    {
        return view('livewire.user.payment-cancelled', [
            'repairRequest' => $this->repairRequest
        ]);
    }
}
