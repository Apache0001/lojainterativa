<?php

namespace Source\App\Api;

use Source\Core\Api;
use Source\Models\User as UserModel;

/**
 * 
 * Class User 
 * 
 * @author Pablo O Mesquita <pablomesquta.agv@gmail.com>
 * @source Source\Core\Api description
 */
class User extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getUsers
     *
     * @return void
     */
    public function getUsers():void
    {
        $users = (new UserModel())->find()->limit(10)->fetch(true);

        $json['message'] = 'Busca realizada com sucesso!';
        
        foreach($users as $user){
            $json['data'][] =  $user->data();
        }

        $this->back($json);
        return;
    }

    /**
     * getUser
     *
     * @param array $data
     * @return void
     */
    public function getUser(array $data):void
    {
        $user = (new UserModel())->findById($data['id']);

        if(empty($user)){
        
            $json['message'] = $this->message->warning('Desculpe, não encontramos o id informado')->render();    
            $this->back($json);
            return;
        }
        
        $json['message'] = $this->message->success('Usuário buscado com sucesso!')->render();   
        $json['data'][] = $user->data();
        $this->back($json);
        return;
    }

    /**
     * postUser
     *
     * @param array $data
     * @return void
     */
    public function postUser(array $data): void
    {

        if(empty($data)){
            $json['message'] = $this->message->success('Nenhum dado informado')->render();
            $this->back($json);
            return;
        }

        $user =  new UserModel();

        $user->first_name = $data['first_name'] ?? '';
        $user->last_name = $data['last_name'] ?? '';
        $user->email =  $data['email'] ?? '';
        $user->password = $data['password'] ?? '';
        $user->level = 1;
        $user->forget = $data['forget'] ?? null;
        $user->genre = $data['genre'] ?? null;
        $user->datebirth = $data['datebirth'] ?? null;
        $user->document = $data['document'] ?? null;
        $user->photo = $data['photo'] ?? null;
        $user->status = $data['status'] ?? null;

        if(!$user->save()){
            $json['message'] = $user->message()->render();
            $this->back($json);
            return;
        }



        $json['message'] = $this->message->success('User cadastrado com sucesso!')->render();
        $json["data"] = $user->data();
        $this->back($json);

        return;
    }

    /**
     * updateUser
     *
     * @param array $data
     * @return void
     */
    public function updateUser(array $data): void
    {
        if(empty($data)){
            $json['message'] = $this->message->success('Nenhum dado informado')->render();
            $this->back($json);
            return;
        }

        $user =  (new UserModel())->findById($data['id']);

        if(empty($user)){
            $json['message'] = $this->message->success('Nenhum usuário foi encontrado')->render();
            $this->back($json);
            return;
        }

        $user->first_name = $data['first_name'] ?? $user->first_name;
        $user->last_name = $data['last_name'] ?? $user->last_name;
        $user->email =  $data['email'] ?? $user->email;
        $user->password = $data['password'] ?? $user->password;
        $user->level = 1;
        $user->forget = $data['forget'] ?? $user->forget;
        $user->genre = $data['genre'] ?? $user->genre;
        $user->datebirth = $data['datebirth'] ?? $user->datebirth;
        $user->document = $data['document'] ?? $user->document;
        $user->photo = $data['photo'] ?? $user->photo;
        $user->status = $data['status'] ?? $user->status;

        if(!$user->save()){
            $json['message'] = $user->message()->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('User atualizado com sucesso!')->render();
        $json["data"] = $user->data();
        $this->back($json);

        return;

    }

    /**
     * deleteUser
     *
     * @return void
     */
    public function deleteUser(array $data): void
    {
        if(empty($data['id'])){
            $json['message'] = $this->message->error('O valor enviado não corresponde ao aceito')->render();
            $this->back($json);
            return;
        }

        $data['id'] =(int)$data['id'];

        if(empty($data['id'])){
            $json['message'] = $this->message->error('O valor enviado precisa ser um número inteiro')->render();
            $this->back($json);
            return;
        }

        $user = new UserModel();

        if(!$user->findById($data['id'])){
            $json['message'] = $this->message->error('Não encontramos este post, por favor, contacte um administrador')->render();
            $this->back($json);
            return;
        }

        if(!$user->delete('id', $data['id'])){
            $json['message'] = $this->message->error('Erro ao deletar user, por favor, contacte um adminstrador')->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('User deletado com sucesso!')->render();
        $this->back($json);
        return;
    }
}