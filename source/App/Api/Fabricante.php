<?php

namespace Source\App\Api;

use Source\Core\Api;
use Source\Models\Fabricante as FabricanteModel;

class Fabricante extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getCategories
     *
     * @return void
     */
    public function getFabricantes(): void
    {
        $fabricantes = (new FabricanteModel())->find()->fetch(true);

        if(!$fabricantes){
            $json[] = $this->message->warning('Não encontramos nenhum resultado para essa busca')->render();
            $this->back($json);
            return;
        }

        foreach($fabricantes as $fabricante){
            $json[] = $fabricante->data();
        }

        $this->back($json);
        return;
    }

    /**
     * getCategory
     *
     * @param array $data
     * @return void
     */
    public function getFabricante(array $data)
    {
        $data = filter_var_array($data);

        $categoria = (new FabricanteModel())->find("id = :id", "id={$data['id']}")->fetch();

        if(!$categoria){
            $json[] = $this->message->warning('Não encontramos nenhum resultado para essa busca')->render();
            $this->back($json);
            return;
        }
        $json[] = $categoria->data();
        $this->back($json);
        return;
    }

    /**
     * postUser
     *
     * @param array $data
     * @return void
     */
    public function postFabricante(array $data): void
    {

        if(empty($data)){
            $json['message'] = $this->message->success('Nenhum dado informado')->render();
            $this->back($json);
            return;
        }

        $fabricante =  new FabricanteModel();

        $fabricante->nome = $data['nome'] ?? '';

        if(!$fabricante->save()){
            $json['message'] = $fabricante->message()->render();
            $this->back($json);
            return;
        }


        $json['message'] = $this->message->success('User cadastrado com sucesso!')->render();
        $json["data"] = $fabricante->data();
        $this->back($json);

        return;
    }

    /**
     * updateFabricante
     *
     * @param array $data
     * @return void
     */
    public function updateFabricante(array $data): void
    {
        if(empty($data)){
            $json['message'] = $this->message->success('Nenhum dado informado')->render();
            $this->back($json);
            return;
        }

        $fabricante =  (new FabricanteModel())->findById($data['id']);

        if(empty($fabricante)){
            $json['message'] = $this->message->success('Nenhum usuário foi encontrado')->render();
            $this->back($json);
            return;
        }

        $fabricante->nome  = $data['nome'] ??   $fabricante->nome ;
    
        if(!$fabricante->save()){
            $json['message'] = $fabricante->message()->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('User atualizado com sucesso!')->render();
        $json["data"] = $fabricante->data();
        $this->back($json);

        return;

    }

    /**
     * deleteCategoria
     *
     * @return void
     */
    public function deleteFabricante(array $data): void
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

        $fabricante = new FabricanteModel();

        if(!$fabricante->findById($data['id'])){
            $json['message'] = $this->message->error('Não encontramos este Fabricante, por favor, contacte um administrador')->render();
            $this->back($json);
            return;
        }

        if(!$fabricante->delete('id', $data['id'])){
            $json['message'] = $this->message->error('Erro ao deletar Fabricante, por favor, contacte um adminstrador')->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('Fabricante deletado com sucesso!')->render();
        $this->back($json);
        return;
    }


}