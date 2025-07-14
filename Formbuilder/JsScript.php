<?php

namespace Formbuilder;

class JsScript {
    private static ?JsScript $instance = null;
    private array $script;
    private array $css;
    private array $inline_vars;
    private int $new_var = 0;

    protected function __construct() {
    }

    public static function instance() : JsScript {
        if ( self::$instance == null )
            self::$instance = new JsScript();
   
        return self::$instance;
    }

    public function add_script(string $script, string $param='after') : JsScript {
        $this->script[$script] = $param;
        return $this;
    }

    public function add_css(string $css, string $param='') : JsScript {
        $this->css[$css] = $param;
        return $this;
    }

    public function var(string $var, string $value) : JsScript {
        $this->inline_vars[$var] = $value;
        return $this;
    }

    public function add_var(string $value) : string {
        $this->new_var += 1;
        $var = "v$this->new_var";
        $this->inline_vars["$var"] = $value;
        return $var;
    }

    public function generate () : string {
        $vars = '';
        $css = '';
        $before_scripts = '';
        $after_scripts = '';
        $js = JAVASCRIPT;
        $cs = STYLESHEET;

        if ( !empty($this->inline_vars) )
            foreach ($this->inline_vars as $key => $value)
                if ( substr($value, 0, 4) == 'new ')
                    $vars .= "var $key=$value;";
                else
                    $vars .= "var $key='$value';";

        if ( !empty($this->css) )
            foreach ($this->css as $key => $value)
                $css .= "<link rel=\"stylesheet\" href=\"$cs/$key.min.css\">";

        if ( !empty($this->script) )
            foreach ($this->script as $key => $value)
                if ( $value == 'before' )
                    $before_scripts .= "<script src=\"$js/$key.min.js\"></script>";
                else
                    $after_scripts .= "<script src=\"$js/$key.min.js\"></script>";

        return $css . $before_scripts . "<script>$vars</script>" . $after_scripts;
    }
}