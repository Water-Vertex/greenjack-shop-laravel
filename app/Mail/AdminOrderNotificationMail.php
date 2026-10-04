<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('New Order #' . $this->order->order_number . ' - ' . config('app.name'))
                    ->view('emails.admin-order-notification')
                    ->with([
                        'order' => $this->order,
                        'adminUrl' =>'',
                        'appName' => config('app.name'),
                    ]);
    }
}