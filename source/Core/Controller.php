<?php

namespace Source\Core;

use Source\Support\Message;
use Source\Core\View;

/**
 * Class Controller
 * 
 * @author Pablo O.Mesquita
 * @package Source\Core
 */
class Controller
{

    /** @var View */
    protected $view;

    /** @var Message */
    protected $message;

    /**
     * construct
     */
    public function __construct(string $pathToViews, $router)
    {
        $this->message = new Message();
        $this->view = new View($pathToViews);
        $this->router = $router;
        
    }
}