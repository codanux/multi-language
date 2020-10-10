<?php

namespace Codanux\MultiLanguage\View\Components;

use Illuminate\View\Component;

class LinksComponent extends Component
{
    /**
     * @var array
     */
    public $translations;

    /**
     * @var string
     */
    public $component;

    public function __construct($translations = [], $component = "jet-nav-link")
    {
        $this->translations = $translations;
        $this->component = $component;
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('multi-language::components.links');
    }
}
