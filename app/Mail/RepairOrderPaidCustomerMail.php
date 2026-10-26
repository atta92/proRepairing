<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RepairOrderPaidCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        $paid = filled($this->order->paid_at);

        return new Envelope(
            subject: ($paid ? 'Repair booking paid — ' : 'Repair booking confirmed — ').$this->order->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.repair-order-paid-customer',
        );
    }
}
