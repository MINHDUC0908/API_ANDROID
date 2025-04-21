<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cartItems;
    public $totalPrice;

    public function __construct($order, $cartItems, $totalPrice)
    {
        $this->order = $order;
        $this->cartItems = $cartItems;
        $this->totalPrice = $totalPrice;
    }

    public function build()
    {
        return $this->subject('Xác nhận đơn hàng')
                    ->view('emails.order_confirmation')
                    ->with([
                        'order' => $this->order,
                        'cartItems' => $this->cartItems,
                        'totalPrice' => $this->totalPrice,
                    ]);
    }
}