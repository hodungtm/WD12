<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $statusText;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->statusText = strtolower($order->status); // ví dụ: "đang giao hàng"
    }

    public function build()
    {
        return $this->subject('Cập nhật đơn hàng #' . $this->order->order_code)
            ->view('emails.order_completed')
            ->with([
                'order' => $this->order,
                'statusText' => $this->statusText
            ]);
    }
}
