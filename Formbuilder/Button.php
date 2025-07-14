<?php

namespace Formbuilder;

use Formbuilder\Wrapper\Wrapper;

class Button {
    private string $class;
    private string $href;
    private string $icon;
    private string $onclick;

    function __construct(string $classes='bootstrap') {
        $this->defaults($classes);
    }

    public function defaults(string $classes='bootstrap') : Button {
        $this->class = Wrapper::classes('button', $classes);
        $this->href='#';
        $this->icon='';
        $this->onclick='';
        return $this;
    }

    public function class(string $class) : Button {
        $this->class = $class;
        return $this;
    }

    public function href(string $href) : Button {
        $this->href = $href;
        return $this;
    }

    public function icon(string $icon, int $width=32, int $height=32) : Button {
        $file = $icon[0] == '/' && defined(IMAGES) ?  $icon : IMAGES.'/'.$icon;
        $this->icon = '<img src="'.$file.'" width="'.$width.'" height="'.$height.'">';        
        return $this;
    }

    public function onclick(string $onclick) : Button {
        $this->onclick = ' onclick="'.$onclick.'"';
        return $this;
    }

    public function button(string $text) : string {
        return '<a href="'.$this->href.'" class="'.$this->class.'" role="button"'.$this->onclick.'>'.$this->icon.$text.'</a>';
    }
}
