<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Filter extends Component
{
    public $col;
    public $refresh;
    public $date;
    public $status;
    public $department;
    public $source;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $col = 2,
        $refresh = false,
        $date = false,
        $status = false,
        $department = true,
        $source = false
        )
    {
        $this->date = $date;
        $this->refresh = $refresh;
        $this->col = $col;
        $this->status = $status;
        $this->department = $department;
        $this->source = $source;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.filter');
    }
}
