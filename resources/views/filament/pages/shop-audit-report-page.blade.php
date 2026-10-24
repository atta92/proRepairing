<x-filament-panels::page>
    {{ $this->content }}

    <div
        class="fi-sc-component mt-6"
        wire:key="audit-report-{{ $this->data['vendor_id'] ?? 'all' }}-{{ $this->summary['line_count'] ?? 0 }}-{{ $this->summary['filtered_gross'] ?? 0 }}"
    >
        @include('filament.pages.shop-audit-report', [
            'summary' => $this->summary,
            'vendorRows' => $this->vendorRows,
            'detailRows' => $this->detailRows,
            'periodFrom' => $this->data['period_from'] ?? null,
            'periodTo' => $this->data['period_to'] ?? null,
            'vendorId' => $this->data['vendor_id'] ?? null,
        ])
    </div>
</x-filament-panels::page>
