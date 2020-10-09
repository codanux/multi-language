<?php

namespace Codanux\MultiLanguage\View\Components;

use Illuminate\View\Component;

class LinksComponent extends Component
{
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
