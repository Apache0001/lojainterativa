<?php

namespace Source\App;

use Source\Core\Controller;

class AuthController extends Controller
{
    public function __construct($router)
    {
        parent::__construct('', $router);
    }

    /**
     * login
     *
     * @param array $data
     * @return void
     */
    public function login(array $data): void
    {
        

        $email = filter_var($data['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_var($data['passwd'], FILTER_DEFAULT);

        echo $email;

        echo json_encode($data);

        return;
    }

}