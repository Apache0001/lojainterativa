<?php

namespace Source\App\Api;

use Source\Core\Api;
use Source\Models\Categoria as CategoriaModel;

class Categoria extends Api
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
    public function getCategorias(): void
    {
        $categorias = (new CategoriaModel())->find()->fetch(true);

        if(!$categorias){
            $json[] = $this->message->warning('Não encontramos nenhum resultado para essa busca')->render();
            $this->back($json);
            return;
        }

        foreach($categorias as $categoria){
            $json[] = $categoria->data();
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
    public function getCategoria(array $data)
    {
        $data = filter_var_array($data);

        $categoria = (new CategoriaModel())->find("id = :id", "id={$data['id']}")->fetch();

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
    public function postCategoria(array $data): void
    {

        if(empty($data)){
            $json['message'] = $this->message->success('Nenhum dado informado')->render();
            $this->back($json);
            return;
        }

        $categoria =  new CategoriaModel();

        $categoria->fabricante_id = $data['fabricante_id'] ?? '';
        $categoria->nome = $data['nome'] ?? '';

        if(!$categoria->save()){
            $json['message'] = $categoria->message()->render();
            $this->back($json);
            return;
        }


        $json['message'] = $this->message->success('Categoria cadastrado com sucesso!')->render();
        $json["data"] = $categoria->data();
        $this->back($json);

        return;
    }

    /**
     * updateCategoria
     *
     * @param array $data
     * @return void
     */
    public function updateCategoria(array $data): void
    {
        if(empty($data)){
            $json['message'] = $this->message->success('Nenhum dado informado')->render();
            $this->back($json);
            return;
        }

        $categoria =  (new CategoriaModel())->findById($data['id']);

        if(empty($categoria)){
            $json['message'] = $this->message->success('Nenhuma Categoria foi encontrado')->render();
            $this->back($json);
            return;
        }

        $categoria->fabricante_id  = $data['fabricante_id'] ??   $categoria->fabricante_id ;
        $categoria->nome  = $data['nome'] ??   $categoria->nome ;
    
        if(!$categoria->save()){
            $json['message'] = $categoria->message()->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('Categoria atualizada com sucesso!')->render();
        $json["data"] = $categoria->data();
        $this->back($json);

        return;

    }

    /**
     * deleteCategoria
     *
     * @return void
     */
    public function deleteCategoria(array $data): void
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

        $categoria = new CategoriaModel();

        if(!$categoria->findById($data['id'])){
            $json['message'] = $this->message->error('Não encontramos esta Categoria, por favor, contacte um administrador')->render();
            $this->back($json);
            return;
        }

        if(!$categoria->delete('id', $data['id'])){
            $json['message'] = $this->message->error('Erro ao deletar Categoria, por favor, contacte um adminstrador')->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('Categoria deletada com sucesso!')->render();
        $this->back($json);
        return;
    }


}