<?php

namespace Moamalat\Pay\View\Components;

use Illuminate\View\Component;

class Pay extends Component
{
    /**
     * Amount value.
     *
     * @var int
     */
    public $amount;

    /**
     * The Merchant Reference.
     *
     * @var string
     */
    public $reference;

    /**
     * Create the component instance.
     *
     * @param  int  $amount
     * @param  string  $reference
     * @return void
     */
    public function __construct($amount = null, $reference = null)
    {
        $this->amount = $amount;
        $this->reference = $reference;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('moamalat-pay::pay');
    }
}
