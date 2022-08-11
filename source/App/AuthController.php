<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;

/**
 * 
 * Class AuthController
 */
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
        
        if(!csrf_verify($data)){
            $json['message']['message'] = 'Você não fez uma requisição segura';
            $json['message']['type'] = 'error';
            echo json_encode($json);
            return;
        }

        $email = filter_var($data['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_var($data['passwd'], FILTER_DEFAULT);

        $auth = new Auth();
        
        if(!$auth->login($email, $password)){
            $json['message']['message'] = $auth->message()->getText();
            $json['message']['type'] = $auth->message()->getType();
            echo json_encode($json);
            return;
        }

        $json['redirect'] = ['url' => url().'/dash/blog'];
        echo json_encode($json);
        return;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function logout(): void
    {
        $auth = new Auth();
        $auth->logout();
        return;
    }

}