<div>
    <form wire:submit.prevent="store">
        @error('quantity') <x-error :message="$message" /> @enderror
        @error('unitCost') <x-error :message="$message" /> @enderror
        @error('product') <x-error :message="$message" /> @enderror

        <div class="flex flex-row space-x-6">
            <div class="space-y-3">
                <x-label for="product" :value="__('Product')" />
                <x-select id="product" wire:model="product">
                    @foreach ($products as $product)
                        <option {{ $loop->first ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="space-y-3">
                <x-label for="quantity" :value="__('Quantity')" />
                <x-input wire:model.debounce.250ms="quantity" id="quantity" type="number" min="1" step="1" autofocus />
            </div>

            <div class="space-y-3">
                <x-label for="unit" :value="__('Unit Cost (£)')" />
                <x-input wire:model.debounce.250ms="unitCost" id="unit" type="text" />
            </div>

            <div class="space-y-3">
                <x-label :value="__('Selling Price')" />
                <div>&pound;{{ $this->sellingPrice }}</div>
            </div>

            <div class="self-center">
                <x-button>{{ __('Record Sale') }}</x-button>
            </div>
        </div>
    </form>
</div>
