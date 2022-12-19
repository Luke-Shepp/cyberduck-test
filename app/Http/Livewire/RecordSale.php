<?php

namespace App\Http\Livewire;

use App\Repositories\Contracts\ProductRepository;
use App\Repositories\Contracts\SaleRepository;
use Cknow\Money\Money;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class RecordSale extends Component
{
    public $quantity = null;

    public $unitCost = null;

    public $product = 1;

    public Collection $products;

    /** @var array[] */
    protected $rules = [
        'quantity' => ['required', 'numeric', 'integer', 'gt:0'],
        'unitCost' => ['required', 'numeric', 'gt:0'],
        'product' => ['required', 'exists:products,id'],
    ];

    /**
     * @param ProductRepository $repository
     * @return void
     */
    public function mount(ProductRepository $repository): void
    {
        $this->products = $repository->all();
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.record-sale');
    }

    /**
     * @param string $field
     * @return void
     * @throws ValidationException
     */
    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    /**
     * @return float
     */
    public function getSellingPriceProperty(): float
    {
        $cost = Money::GBP(floatval($this->unitCost) * 100)
            ->multiply(intval($this->quantity));

        if ($cost->isZero()) {
            return 0;
        }

        return $cost->divide((string) (1 - ($this->margin / 100)))
            ->add(Money::GBP($this->shippingCost))
            ->getAmount() / 100;
    }

    /**
     * @param SaleRepository $repository
     * @return void
     */
    public function store(SaleRepository $repository): void
    {
        $this->validate();

        $repository->create([
            'quantity' => $this->quantity,
            'unit_cost' => $this->unitCost,
            'selling_price' => $this->sellingPrice,
            'product_id' => $this->product,
            'margin' => $this->margin,
            'shipping_cost' => $this->shippingCost,
        ]);

        $this->emit('saleCreated');

        $this->quantity = null;
        $this->unitCost = null;
    }

    /**
     * @return int
     */
    public function getMarginProperty(): int
    {
        if (empty($this->product)) {
            return 0;
        }

        return $this->products->firstWhere('id', $this->product)?->margin ?? 0;
    }

    /**
     * @return int
     */
    public function getShippingCostProperty(): int
    {
        if (empty($this->product)) {
            return 0;
        }

        return $this->products->firstWhere('id', $this->product)?->shipping_cost ?? 0;
    }
}
