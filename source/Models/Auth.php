<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Core\Session;

/**
 * Class Auth
 * 
 * @author Pablo O.Mesquita <pablomesquita.agv@gmail.com>
 * @package Source\Models
 */
class Auth extends Model
{
    /**
     * user
     *
     * @return null|User
     */
    public static function user(): ?User
    {
        $session = new Session();
        if(!$session->has("authUser")){
            return null;
        }

        return (new User())->findById($session->authUser);
    }

    /**
     * verify
     *
     * @param string $email
     * @param string $password
     * @return null|User
     */
    public function verify(string $email, string $password): ?User
    {
        $user = (new User())->findByEmail($email);

        if(!$user){
            $this->message->error('O e-mail informado não está cadastrado');
            return null;
        }

        if(!passwd_verify($password, $user->password)){
            $this->message->error('A senha informada não confere');
            return null;
        }

        if(passwd_rehash($user->password)){
            $user->password =  $password;
            $user->save();
        }

        return $user;

    }

    /**
     * login
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function login(string $email, string $password): bool
    {
        $user = $this->verify($email, $password);

        if(!$user){
            return false;
        }

        //LOGIN
        (new Session())->set('authUser', $user->id);
        return true;
    }

    /**
     * logout
     *
     * @return void
     */
    public static function logout(): void
    {
        $session = new Session();
        $session->unset('authUser');
    }

    
}