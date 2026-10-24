<?php

namespace App\Mail;

use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShopOrderFulfilmentUpdatedCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  list<array{item: ShopOrderItem, from: string, to: string}>  $changes
     */
    public function __construct(
        public ShopOrder $order,
        public array $changes,
        public ?Vendor $vendor = null,
    ) {}

    public function envelope(): Envelope
    {
        $statuses = collect($this->changes)
            ->pluck('to')
            ->unique()
            ->map(fn (string $status) => ShopOrderItem::FULFILMENT_STATUSES[$status] ?? ucfirst($status))
            ->values()
            ->all();

        $statusPart = $statuses !== [] ? implode(', ', $statuses) : 'updated';

        return new Envelope(
            subject: 'Order '.$this->order->reference.' — '.$statusPart,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.shop-order-fulfilment-updated-customer',
        );
    }
}
