<div class="w-full">
    <table class="w-full">
        <thead class="border-b bg-gray-50">
            <tr>
                <x-th>{{ __('Quantity') }}</x-th>
                <x-th>{{ __('Unit Cost') }}</x-th>
                <x-th>{{ __('Selling Price') }}</x-th>
            </tr>
        </thead>
        <tbody>
            @forelse($this->sales as $sale)
                <tr class="bg-white border-b">
                    <x-td>{{ $sale->quantity }}</x-td>
                    <x-td>&pound;{{ $sale->unit_cost }}</x-td>
                    <x-td>&pound;{{ $sale->selling_price }}</x-td>
                </tr>
            @empty
                <tr class="bg-white border-b">
                    <x-td colspan="3">{{ __('No Sales') }}</x-td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
