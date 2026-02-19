<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShareButtons extends Component
{
    public $url;
    public $title;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($url, $title = '')
    {
        $this->url = $url;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.share-buttons');
    }
}
