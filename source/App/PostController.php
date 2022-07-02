<?php

namespace Source\App;

use Source\Core\Controller;

class PostController extends Controller
{

    public function __construct()
    {
        parent::__construct(__DIR__."/../../themes/".CONF_VIEW_THEME_POST."/");
    }

    public function home()
    {
       echo $this->view->render('home', []);
    }
}