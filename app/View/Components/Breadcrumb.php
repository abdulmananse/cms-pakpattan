<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  boolean  $title
     * @param  array  $button
     * @return void
     */
    public function __construct($title = '', $breadcrumbs = [], $button = [], $buttons = [])
    {
        $this->title = $title;
        $this->breadcrumbs = $breadcrumbs;
        $this->button = $button;
        $this->buttons = $buttons;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.breadcrumb', ['title' => $this->title, 'breadcrumbs' => $this->breadcrumbs, 'button' => $this->button, 'buttons' => $this->buttons]);
    }
}
