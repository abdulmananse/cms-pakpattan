<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Filter extends Component
{
    public $col;
    public $refresh;
    public $date;
    public $status;
    public $category;
    public $department;
    public $source;
    public $dateValue;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $col = 2,
        $dateValue = 'custom',
        $refresh = false,
        $date = false,
        $status = false,
        $category = false,
        $department = false,
        $source = false
        )
    {
        $this->date = $date;
        $this->refresh = $refresh;
        $this->dateValue = $dateValue;
        $this->col = $col;
        $this->status = $status;
        $this->category = $category;
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
