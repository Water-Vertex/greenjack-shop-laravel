<?php

namespace App\Livewire\User;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\PrintingRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

#[Layout('components.user-layout')]
class PrintingMarketing extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $service = '';
    public $message = '';
    public $showModal = false;
    public $modalServiceTitle = '';
    public $modalServiceDesc = '';
    public $successMessage = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'service' => 'required|string|max:255',
        'message' => 'nullable|string'
    ];

    protected $messages = [
        'name.required' => 'Please enter your full name.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'service.required' => 'Service information is required.'
    ];

    public function openModal($serviceName, $serviceDesc)
    {
        $this->modalServiceTitle = $serviceName;
        $this->modalServiceDesc = $serviceDesc;
        $this->service = $serviceName;
        $this->showModal = true;

        // Reset form fields but keep service
        $this->reset(['name', 'email', 'phone', 'message']);
        $this->service = $serviceName;

        // Dispatch browser event to show modal
        $this->dispatch('show-modal');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'email', 'phone', 'service', 'message']);
        $this->dispatch('hide-modal');
    }

    public function submitInquiry()
    {
        $this->validate();

        try {
            // Save to database
            $printingRequest = PrintingRequest::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'service' => $this->service,
                'message' => $this->message,
                'status' => 'pending'
            ]);

            // Optional: Send email notification
            // You can uncomment this after setting up mail configuration
            /*
            Mail::send('emails.printing-inquiry', [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'service' => $this->service,
                'message' => $this->message
            ], function ($mail) {
                $mail->to('[email protected]')
                     ->subject('New Printing Service Inquiry');
            });
            */

            // Show success message
            $this->successMessage = 'Thank you! Your request has been sent successfully.';

            // Reset form and close modal after success
            $this->reset(['name', 'email', 'phone', 'message']);
            $this->showModal = false;
            $this->dispatch('hide-modal');

        } catch (\Exception $e) {
            Log::error('Printing Request Error: ' . $e->getMessage());
            $this->dispatch('show-error', 'Sorry, there was an error submitting your request. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user.printing-marketing', [
            'services' => [
                'Document Printing' => 'High-quality document printing for reports, manuals, proposals, and presentations. Fast turnaround with premium paper stocks.',
                'Business Cards' => 'Premium business cards with matte, gloss, foil accents, or eco-friendly finishes. Create lasting first impressions.',
                'Signs, Banners & Posters' => 'Indoor & outdoor signage, vinyl banners, pull-up displays for trade shows, retail, and events.',
                'Marketing Materials' => 'Flyers, brochures, catalogs, and door hangers with data-driven designs that convert leads.',
                'Cards & Invitations' => 'Elegant wedding invites, greeting cards, thank you notes with foil stamping and letterpress options.',
                'Labels & Stickers' => 'Waterproof, kiss-cut, or roll labels perfect for packaging, branding, and giveaways.',
                'Envelope & Stationery' => 'Custom envelopes, letterheads, notepads for sophisticated corporate correspondence.',
                'Photo Gifts & Business Solutions' => 'Photo albums, calendars, mugs, and B2B bulk solutions for corporate gifts.'
            ]
        ]);
    }
}
