<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        $statusLabels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];

        return $this->subject('Order Status Update - ' . config('app.name'))
                    ->view('emails.order-status-update')
                    ->with([
                        'order' => $this->order,
                        'oldStatus' => $statusLabels[$this->oldStatus] ?? ucfirst($this->oldStatus),
                        'newStatus' => $statusLabels[$this->newStatus] ?? ucfirst($this->newStatus),
                        'orderUrl' => '',
                        'appName' => config('app.name'),
                    ]);
    }
}