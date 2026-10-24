@php
    $money = fn ($value) => '£'.number_format((float) $value, 2);
    $isVendorFilter = filled($summary['vendor_id'] ?? null);
    $vendorTitle = $isVendorFilter
        ? ($summary['vendor_name'] ?? 'Vendor')
        : 'All vendors';

    $summaryPairs = [
        ['Orders', (int) ($summary['order_count'] ?? 0)],
        ['Line items', (int) ($summary['line_count'] ?? 0)],
        ['Gross sales', $money($summary['filtered_gross'] ?? 0)],
        ['Commission', $money($summary['filtered_commission'] ?? 0)],
        [
            ($summary['is_own_shop'] ?? false) ? 'Own shop earnings' : 'Vendor net / own earnings',
            $money($summary['filtered_vendor_net'] ?? 0),
        ],
        ['Unpaid to sellers', $money($summary['seller_net_unpaid'] ?? 0)],
        ['Paid out to sellers', $money($summary['paid_out_to_sellers'] ?? 0)],
    ];

    if (! $isVendorFilter) {
        $summaryPairs[] = ['Shipping retained', $money($summary['shipping_retained'] ?? 0)];
    }
@endphp

<style>
    .audit-wrap { display: grid; gap: 1.25rem; }
    .audit-sheet {
        overflow: hidden;
        border: 1px solid #bdbdbd;
        background: #fff;
    }
    .audit-sheet__tab {
        display: inline-flex;
        align-items: center;
        border: 1px solid #bdbdbd;
        border-bottom: 0;
        background: #217346;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.35rem 0.9rem;
        border-radius: 0.25rem 0.25rem 0 0;
    }
    .audit-sheet__meta {
        border-bottom: 1px solid #bdbdbd;
        background: #f2f2f2;
        color: #555;
        font-size: 0.75rem;
        padding: 0.4rem 0.65rem;
    }
    .audit-scroll { overflow-x: auto; max-width: 100%; }
    .audit-grid {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
        font-size: 0.8125rem;
        line-height: 1.3;
        color: #111;
        background: #fff;
    }
    .audit-grid th,
    .audit-grid td {
        border: 1px solid #c8c8c8;
        padding: 0.4rem 0.55rem;
        vertical-align: middle;
    }
    .audit-grid thead th {
        background: #217346;
        color: #fff;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }
    .audit-grid tbody tr:nth-child(even) td { background: #f3faf5; }
    .audit-grid tbody tr:hover td { background: #e7f6ea; }
    .audit-grid .num {
        text-align: right;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }
    .audit-grid .label-cell {
        background: #eaf6ee !important;
        font-weight: 600;
        width: 58%;
    }
    .audit-grid .empty {
        text-align: center;
        color: #666;
        padding: 1.1rem 0.75rem;
    }
    .dark .audit-sheet {
        border-color: rgba(255,255,255,0.18);
        background: rgba(255,255,255,0.03);
    }
    .dark .audit-sheet__meta {
        border-color: rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.7);
    }
    .dark .audit-grid {
        background: transparent;
        color: rgba(255,255,255,0.92);
    }
    .dark .audit-grid th,
    .dark .audit-grid td { border-color: rgba(255,255,255,0.14); }
    .dark .audit-grid tbody tr:nth-child(even) td { background: rgba(33,115,70,0.12); }
    .dark .audit-grid tbody tr:hover td { background: rgba(33,115,70,0.22); }
    .dark .audit-grid .label-cell { background: rgba(33,115,70,0.28) !important; }
</style>

<div class="audit-wrap">
    <div>
        <div class="audit-sheet__tab">Summary</div>
        <div class="audit-sheet">
            <div class="audit-sheet__meta">
                {{ $vendorTitle }} · {{ $periodFrom ?? '—' }} → {{ $periodTo ?? '—' }}
            </div>
            <div class="audit-scroll">
                <table class="audit-grid" style="min-width: 420px;">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th class="num">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($summaryPairs as [$label, $value])
                            <tr>
                                <td class="label-cell">{{ $label }}</td>
                                <td class="num">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @unless ($isVendorFilter)
        <div>
            <div class="audit-sheet__tab">Vendors</div>
            <div class="audit-sheet">
                <div class="audit-sheet__meta">One row per vendor</div>
                <div class="audit-scroll">
                    <table class="audit-grid" style="min-width: 720px;">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Type</th>
                                <th class="num">Lines</th>
                                <th class="num">Gross</th>
                                <th class="num">Commission</th>
                                <th class="num">Vendor net</th>
                                <th class="num">Unpaid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vendorRows as $row)
                                <tr>
                                    <td>{{ $row['vendor_name'] }}</td>
                                    <td>{{ $row['sale_type'] }}</td>
                                    <td class="num">{{ $row['lines'] }}</td>
                                    <td class="num">{{ $money($row['gross']) }}</td>
                                    <td class="num">{{ $money($row['commission']) }}</td>
                                    <td class="num">{{ $money($row['vendor_net']) }}</td>
                                    <td class="num">{{ $money($row['unpaid_net']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty">No data for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endunless

    <div>
        <div class="audit-sheet__tab">Line details</div>
        <div class="audit-sheet">
            <div class="audit-sheet__meta">
                Showing up to 100 rows · Excel download has the full file
            </div>
            <div class="audit-scroll">
                <table class="audit-grid" style="min-width: 980px;">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            @unless ($isVendorFilter)
                                <th>Vendor</th>
                            @endunless
                            <th>Product</th>
                            <th class="num">Qty</th>
                            <th class="num">Gross</th>
                            <th class="num">Commission %</th>
                            <th class="num">Commission £</th>
                            <th class="num">Vendor net £</th>
                            <th>Payout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (array_slice($detailRows, 0, 100) as $row)
                            <tr>
                                <td>{{ $row['order_reference'] }}</td>
                                <td style="white-space: nowrap;">{{ $row['order_date'] }}</td>
                                @unless ($isVendorFilter)
                                    <td>{{ $row['vendor_name'] }}</td>
                                @endunless
                                <td title="{{ $row['product_name'] }}">
                                    {{ \Illuminate\Support\Str::limit($row['product_name'], 40) }}
                                </td>
                                <td class="num">{{ $row['quantity'] }}</td>
                                <td class="num">{{ $money($row['line_gross']) }}</td>
                                <td class="num">{{ number_format((float) $row['commission_rate'], 2) }}</td>
                                <td class="num">{{ $money($row['commission_amount']) }}</td>
                                <td class="num">{{ $money($row['vendor_net']) }}</td>
                                <td>{{ $row['payout_status'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isVendorFilter ? 9 : 10 }}" class="empty">No data for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
