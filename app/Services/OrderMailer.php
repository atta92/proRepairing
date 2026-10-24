<?php

namespace App\Services;

use App\Mail\RepairOrderPaidAdminMail;
use App\Mail\RepairOrderPaidCustomerMail;
use App\Mail\ShopOrderFulfilmentUpdatedCustomerMail;
use App\Mail\ShopOrderPaidAdminMail;
use App\Mail\ShopOrderPaidCustomerMail;
use App\Models\Order;
use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderMailer
{
    public function shopOrderPaid(ShopOrder $order): void
    {
        $order->loadMissing('customer', 'items');

        $customerEmail = $order->customer?->email;
        if (filled($customerEmail)) {
            $this->safeSend($customerEmail, new ShopOrderPaidCustomerMail($order));
        }

        foreach ($this->adminEmails() as $adminEmail) {
            $this->safeSend($adminEmail, new ShopOrderPaidAdminMail($order));
        }
    }

    public function repairOrderPaid(Order $order): void
    {
        $order->loadMissing('customer', 'items', 'device');

        $customerEmail = $order->customer?->email;
        if (filled($customerEmail)) {
            $this->safeSend($customerEmail, new RepairOrderPaidCustomerMail($order));
        }

        foreach ($this->adminEmails() as $adminEmail) {
            $this->safeSend($adminEmail, new RepairOrderPaidAdminMail($order));
        }
    }

    /**
     * @param  list<array{item: ShopOrderItem, from: string, to: string}>  $changes
     */
    public function shopOrderFulfilmentUpdated(ShopOrder $order, array $changes, ?Vendor $vendor = null): void
    {
        if ($changes === []) {
            return;
        }

        $order->loadMissing('customer');

        $customerEmail = $order->customer?->email;
        if (! filled($customerEmail)) {
            return;
        }

        $this->safeSend(
            $customerEmail,
            new ShopOrderFulfilmentUpdatedCustomerMail($order, $changes, $vendor)
        );
    }

    /**
     * @return list<string>
     */
    private function adminEmails(): array
    {
        $emails = User::query()
            ->where('is_admin', true)
            ->pluck('email')
            ->filter()
            ->values()
            ->all();

        $contact = SiteSetting::current()->contact_email;
        if (filled($contact) && ! in_array($contact, $emails, true)) {
            $emails[] = $contact;
        }

        return $emails;
    }

    private function safeSend(string $email, object $mailable): void
    {
        try {
            Mail::to($email)->send($mailable);
        } catch (\Throwable $e) {
            Log::warning('Failed to send order email', [
                'to' => $email,
                'mailable' => $mailable::class,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
