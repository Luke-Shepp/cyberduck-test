<?php

namespace App\Http\Livewire;

use App\Repositories\Contracts\SaleRepository;
use Cknow\Money\Money;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class RecordSale extends Component
{
    public $quantity = null;

    public $unitCost = null;

    /** @var array[] */
    protected $rules = [
        'quantity' => ['required', 'numeric', 'integer', 'gt:0'],
        'unitCost' => ['required', 'numeric', 'gt:0'],
    ];

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

        return $cost->divide((string) (1 - (config('sales.margin') / 100)))
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
        return config('sales.margin');
    }

    /**
     * @return int
     */
    public function getShippingCostProperty(): int
    {
        return config('sales.shipping_cost');
    }
}
