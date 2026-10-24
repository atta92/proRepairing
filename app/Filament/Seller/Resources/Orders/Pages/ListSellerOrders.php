<?php

namespace App\Filament\Seller\Resources\Orders\Pages;

use App\Filament\Seller\Resources\Orders\SellerOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListSellerOrders extends ListRecords
{
    protected static string $resource = SellerOrderResource::class;
}
