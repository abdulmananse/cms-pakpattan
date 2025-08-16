<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{

    protected $keys;
    public $datatable_class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($keys = null, $datatables = 'user-list-table')
    {
        $this->keys = $keys;
        $this->datatable_class = $datatables;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table', ['keys' => $this->keys, 'datatable_class' => $this->datatable_class]);
    }
}
