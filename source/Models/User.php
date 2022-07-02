<?php

namespace Source\Models;

use Source\Core\Model;

class User extends Model
{
    public function __construct()
    {
        parent::__construct("users",[''],['']);
    }

    /**
     * findByEmail
     *
     * @param string $email
     * @return null|User
     */
    public function findByEmail(string $email): ?User
    {
        $find = $this->find("email = :email", "email:{$email}");

        return $find->fetch();
    }












    /**
     * saveUser
     *
     * @return boolean
     */
    public function save(): bool
    {

        /* if(!$this->required()){
            $this->message->warning('Preencha todos os campos para continuar');
            return false;
        } */

        if(!is_passwd($this->password)){
            $this->message->warning('A senha deve ter ser de 8 ou mais caractere');
            return false;
        } 

        if(!empty($this->password)){
            $this->password = passwd($this->password);
        }
       

        /** Update */
        if(!empty($this->id)){
           $id = $this->id;
           $this->update($this->safe(), 'id=:id', "id={$id}");
           if($this->fail()){
               $this->message->error('Erro ao atualizazr, verifique os dados');
               return false;
           }
        }

        /** Create */
        if(empty($this->id)){
           
            $id = $this->create($this->safe());

            if($this->fail()){
                $this->message->error('Erro ao cadastrar, verifique os dados');
                return false;
            }
        }

        $this->data = $this->findById($id)->data();
        return true;

        parent::save();
    }
}