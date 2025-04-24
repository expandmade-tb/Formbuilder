<?php

namespace controller;

class BaseController {
    protected array $data = [];

    function __construct() {
        $this->data['css_files'] = [
            STYLESHEET.'/styles.min.css',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'            
        ];

        $this->data['js_files'] = ["https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"];
        $this->data['title'] = 'Formbuilder Demo';
    }

    /**
     * @return void
     */
    protected function index() {} 

    /**
     * @return void
     */
    protected function view (string $view, array $data=[]) { 
        if ( !empty($data) )
            extract($data);
        else
            extract($this->data);

        $file = APP.'/views/'.str_replace('\\', DIRECTORY_SEPARATOR, $view).'.php';
        require_once $file;
    }
}