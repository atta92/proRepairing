<?php

namespace App\Filament\Pages;

use App\Services\ShopAuditReport;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ShopAuditReportPage extends Page
{
    protected string $view = 'filament.pages.shop-audit-report-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Shop';

    protected static ?string $navigationLabel = 'Audit report';

    protected static ?string $title = 'Shop audit report';

    protected static ?string $slug = 'shop-audit-report';

    protected static ?int $navigationSort = 5;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    /**
     * @var array<string, float|int|string|bool|null>
     */
    public array $summary = [];

    /**
     * @var list<array<string, mixed>>
     */
    public array $vendorRows = [];

    /**
     * @var list<array<string, mixed>>
     */
    public array $detailRows = [];

    /**
     * @var list<array<string, mixed>>
     */
    public array $orderRows = [];

    public function mount(): void
    {
        $this->form->fill([
            'period_from' => now()->startOfMonth()->toDateString(),
            'period_to' => now()->toDateString(),
            'vendor_id' => null,
        ]);

        $this->refreshReport();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filters')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('period_from')
                            ->label('From')
                            ->native(false)
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn () => $this->refreshReport()),
                        DatePicker::make('period_to')
                            ->label('To')
                            ->native(false)
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn () => $this->refreshReport()),
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->placeholder('All vendors')
                            ->options(fn (): array => app(ShopAuditReport::class)->vendorOptions())
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(fn () => $this->refreshReport()),
                    ]),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('refreshReport')
            ->footer([
                Actions::make([
                    Action::make('refreshReport')
                        ->label('Run report')
                        ->submit('refreshReport'),
                    Action::make('exportExcel')
                        ->label('Download Excel')
                        ->color('gray')
                        ->action(fn () => $this->exportExcel()),
                ]),
            ]);
    }

    public function refreshReport(): void
    {
        $raw = $this->form->getRawState();
        $raw = is_array($raw) ? $raw : (method_exists($raw, 'toArray') ? $raw->toArray() : []);
        $state = array_merge($this->data ?? [], $raw);
        $this->data = $state;

        $fromValue = $state['period_from'] ?? null;
        $toValue = $state['period_to'] ?? null;

        if (! filled($fromValue) || ! filled($toValue)) {
            return;
        }

        $service = app(ShopAuditReport::class);
        $from = $service->parseDate($fromValue);
        $to = $service->parseDate($toValue, endOfDay: true);
        $vendorRaw = $state['vendor_id'] ?? null;
        $vendorId = ($vendorRaw === null || $vendorRaw === '') ? null : (int) $vendorRaw;

        // Keep filter values as plain dates (avoids invalid Windows filenames on export).
        if ($from) {
            $this->data['period_from'] = $from->toDateString();
        }
        if ($to) {
            $this->data['period_to'] = $to->toDateString();
        }

        if ($from && $to && $from->gt($to)) {
            Notification::make()
                ->title('Invalid period')
                ->body('“From” must be on or before “To”.')
                ->danger()
                ->send();

            return;
        }

        $this->summary = $service->summary($from, $to, $vendorId);
        $this->vendorRows = $service->vendorBreakdown($from, $to, $vendorId)->all();
        $this->detailRows = $service->detailRows($from, $to, $vendorId)->all();
        $this->orderRows = $service->orderRows($from, $to, $vendorId)->all();

        // Guarantee orders sheet matches visible line details even if orderRows is empty.
        if ($this->orderRows === [] && $this->detailRows !== []) {
            $this->orderRows = $this->ordersFromDetailRows(collect($this->detailRows));
        }
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $detailRows
     * @return list<array<string, mixed>>
     */
    protected function ordersFromDetailRows(Collection $detailRows): array
    {
        return $detailRows
            ->groupBy('order_reference')
            ->map(function (Collection $lines) {
                $first = $lines->first();

                return [
                    'order_reference' => $first['order_reference'] ?? null,
                    'order_date' => $first['order_date'] ?? null,
                    'paid_at' => $first['order_date'] ?? null,
                    'order_status' => $first['order_status'] ?? null,
                    'fulfilment' => null,
                    'payment_method' => null,
                    'customer_name' => $first['customer_name'] ?? null,
                    'customer_email' => $first['customer_email'] ?? null,
                    'customer_phone' => null,
                    'vendors' => $lines->pluck('vendor_name')->unique()->filter()->implode(', '),
                    'products' => $lines->map(fn (array $line) => ($line['product_name'] ?? '').' × '.($line['quantity'] ?? 0))->implode('; '),
                    'line_count' => $lines->count(),
                    'items_qty' => (int) $lines->sum('quantity'),
                    'line_gross' => round((float) $lines->sum('line_gross'), 2),
                    'commission_amount' => round((float) $lines->sum('commission_amount'), 2),
                    'vendor_net' => round((float) $lines->sum('vendor_net'), 2),
                    'order_subtotal' => round((float) $lines->sum('line_gross'), 2),
                    'shipping_amount' => null,
                    'discount_amount' => null,
                    'order_total' => null,
                    'coupon_code' => null,
                    'notes' => null,
                    'stripe_payment_intent' => null,
                ];
            })
            ->values()
            ->all();
    }

    public function exportExcel(): BinaryFileResponse
    {
        $this->refreshReport();

        $from = $this->safeDateLabel($this->data['period_from'] ?? null, 'start');
        $to = $this->safeDateLabel($this->data['period_to'] ?? null, 'end');
        $vendorSlug = filled($this->summary['vendor_name'] ?? null)
            ? '-'.Str::slug((string) $this->summary['vendor_name'])
            : '';
        $filename = 'shop-audit-report'.$vendorSlug.'-'.$from.'-to-'.$to.'.xlsx';

        $summary = $this->summary;
        $vendorRows = $this->vendorRows;
        $detailRows = $this->detailRows;
        $orderRows = $this->orderRows !== []
            ? $this->orderRows
            : $this->ordersFromDetailRows(collect($detailRows));
        $vendorLabel = $summary['vendor_name'] ?? 'All vendors';

        $productsByOrder = collect($detailRows)
            ->groupBy('order_reference')
            ->map(fn (Collection $lines) => $lines
                ->map(fn (array $line) => ($line['product_name'] ?? '').' × '.($line['quantity'] ?? 0))
                ->implode('; '));

        $spreadsheet = new Spreadsheet();

        $summarySheet = $spreadsheet->getActiveSheet();
        $summarySheet->setTitle('Summary');
        $summarySheet->fromArray([
            ['Shop audit report'],
            ['Vendor', $vendorLabel],
            ['Period from', $from],
            ['Period to', $to],
            ['Generated at', now()->format('Y-m-d H:i:s')],
            [],
            ['Metric', 'Amount (GBP)'],
            ['Orders', $summary['order_count'] ?? 0],
            ['Line items', $summary['line_count'] ?? 0],
            ['Gross sales', $summary['filtered_gross'] ?? 0],
            ['Commission', $summary['filtered_commission'] ?? 0],
            ['Vendor net / own earnings', $summary['filtered_vendor_net'] ?? 0],
            ['Unpaid to sellers', $summary['seller_net_unpaid'] ?? 0],
            ['Paid out to sellers', $summary['paid_out_to_sellers'] ?? 0],
            ['Shipping retained', $summary['shipping_retained'] ?? 0],
        ]);
        $summarySheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $summarySheet->getStyle('A7:B7')->getFont()->setBold(true);
        $summarySheet->getStyle('B10:B15')->getNumberFormat()->setFormatCode('£#,##0.00');
        foreach (range('A', 'B') as $col) {
            $summarySheet->getColumnDimension($col)->setAutoSize(true);
        }

        $ordersSheet = $spreadsheet->createSheet();
        $ordersSheet->setTitle('Orders');
        $ordersSheet->fromArray([[
            'Order reference',
            'Order date',
            'Paid at',
            'Status',
            'Fulfilment',
            'Payment method',
            'Customer name',
            'Customer email',
            'Customer phone',
            'Vendors',
            'Products',
            'Lines',
            'Items qty',
            'Line gross £',
            'Commission £',
            'Vendor net £',
            'Order subtotal £',
            'Shipping £',
            'Discount £',
            'Order total £',
            'Coupon',
            'Notes',
            'Stripe payment intent',
        ]]);
        $orderRowIndex = 2;
        foreach ($orderRows as $row) {
            $reference = $row['order_reference'] ?? null;
            $ordersSheet->fromArray([[
                $reference,
                $row['order_date'] ?? null,
                $row['paid_at'] ?? null,
                $row['order_status'] ?? null,
                $row['fulfilment'] ?? null,
                $row['payment_method'] ?? null,
                $row['customer_name'] ?? null,
                $row['customer_email'] ?? null,
                $row['customer_phone'] ?? null,
                $row['vendors'] ?? null,
                $row['products'] ?? $productsByOrder->get($reference),
                $row['line_count'] ?? null,
                $row['items_qty'] ?? null,
                $row['line_gross'] ?? null,
                $row['commission_amount'] ?? null,
                $row['vendor_net'] ?? null,
                $row['order_subtotal'] ?? null,
                $row['shipping_amount'] ?? null,
                $row['discount_amount'] ?? null,
                $row['order_total'] ?? null,
                $row['coupon_code'] ?? null,
                $row['notes'] ?? null,
                $row['stripe_payment_intent'] ?? null,
            ]], null, 'A'.$orderRowIndex);
            $orderRowIndex++;
        }
        $ordersSheet->getStyle('A1:W1')->getFont()->setBold(true);
        if ($orderRowIndex > 2) {
            $ordersSheet->getStyle('N2:T'.($orderRowIndex - 1))->getNumberFormat()->setFormatCode('£#,##0.00');
        }
        foreach (range('A', 'W') as $col) {
            $ordersSheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (($summary['vendor_id'] ?? null) === null) {
            $vendorSheet = $spreadsheet->createSheet();
            $vendorSheet->setTitle('Vendors');
            $vendorSheet->fromArray([
                ['Vendor', 'Type', 'Lines', 'Gross', 'Commission', 'Vendor net', 'Unpaid net'],
            ]);
            $vendorRowIndex = 2;
            foreach ($vendorRows as $row) {
                $vendorSheet->fromArray([[
                    $row['vendor_name'],
                    $row['sale_type'],
                    $row['lines'],
                    $row['gross'],
                    $row['commission'],
                    $row['vendor_net'],
                    $row['unpaid_net'],
                ]], null, 'A'.$vendorRowIndex);
                $vendorRowIndex++;
            }
            $vendorSheet->getStyle('A1:G1')->getFont()->setBold(true);
            if ($vendorRowIndex > 2) {
                $vendorSheet->getStyle('D2:G'.($vendorRowIndex - 1))->getNumberFormat()->setFormatCode('£#,##0.00');
            }
            foreach (range('A', 'G') as $col) {
                $vendorSheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $linesSheet = $spreadsheet->createSheet();
        $linesSheet->setTitle('Line details');
        $linesSheet->fromArray([[
            'Order reference',
            'Order date',
            'Order status',
            'Customer name',
            'Customer email',
            'Vendor',
            'Sale type',
            'Product',
            'Options',
            'Qty',
            'Unit price',
            'Line gross',
            'Commission %',
            'Commission £',
            'Vendor net £',
            'Fulfilment status',
            'Payout status',
            'Payout reference',
            'Payout paid at',
        ]]);
        $lineRowIndex = 2;
        foreach ($detailRows as $row) {
            $linesSheet->fromArray([[
                $row['order_reference'],
                $row['order_date'],
                $row['order_status'],
                $row['customer_name'],
                $row['customer_email'],
                $row['vendor_name'],
                $row['sale_type'],
                $row['product_name'],
                $row['options'],
                $row['quantity'],
                $row['unit_price'],
                $row['line_gross'],
                $row['commission_rate'],
                $row['commission_amount'],
                $row['vendor_net'],
                $row['fulfilment_status'],
                $row['payout_status'],
                $row['payout_reference'],
                $row['payout_paid_at'],
            ]], null, 'A'.$lineRowIndex);
            $lineRowIndex++;
        }
        $linesSheet->getStyle('A1:S1')->getFont()->setBold(true);
        if ($lineRowIndex > 2) {
            $linesSheet->getStyle('K2:L'.($lineRowIndex - 1))->getNumberFormat()->setFormatCode('£#,##0.00');
            $linesSheet->getStyle('N2:O'.($lineRowIndex - 1))->getNumberFormat()->setFormatCode('£#,##0.00');
            $linesSheet->getStyle('M2:M'.($lineRowIndex - 1))->getNumberFormat()->setFormatCode('0.00');
        }
        foreach (range('A', 'S') as $col) {
            $linesSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);

        $directory = storage_path('app/audit-exports');
        File::ensureDirectoryExists($directory);

        $tempPath = $directory.DIRECTORY_SEPARATOR.Str::uuid()->toString().'.xlsx';

        (new Xlsx($spreadsheet))->save($tempPath);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return response()
            ->download(
                $tempPath,
                $filename,
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            )
            ->deleteFileAfterSend(true);
    }

    protected function safeDateLabel(mixed $value, string $fallback): string
    {
        if (! filled($value)) {
            return $fallback;
        }

        try {
            return \Carbon\Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable) {
            return Str::slug((string) $value) ?: $fallback;
        }
    }
}
