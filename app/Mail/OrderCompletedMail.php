<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

     public $order;
    /**
     * Create a new message instance.
     */
    public function __construct(Order   $order)
    {
         $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Completed Mail',
        );
    }

    public function build()
    {
        return $this->subject('Đơn hàng của bạn đã hoàn thành')
                    ->view('emails.order_completed');
    }
}
