<?php

namespace App\Http\Livewire;

use App\Repositories\Contracts\SaleRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowPreviousSales extends Component
{
    /** @var Collection */
    public Collection $sales;

    /** @var string[] */
    protected $listeners = [
        'saleCreated' => 'loadSales'
    ];

    /**
     * @param SaleRepository $repository
     * @return void
     */
    public function mount(SaleRepository $repository): void
    {
        $this->loadSales($repository);
    }

    public function loadSales(SaleRepository $repository)
    {
        $this->sales = $repository->all()->reverse();
    }

    /**
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('livewire.show-previous-sales');
    }
}
