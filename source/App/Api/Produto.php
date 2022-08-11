<?php

namespace Source\App\Api;

use Source\Core\Api;
use Source\Models\Produto as ProdutoModel;

/**
 * 
 * Class User 
 * 
 * @author Pablo O Mesquita <pablomesquta.agv@gmail.com>
 * @source Source\Core\Api description
 */
class Produto extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getProdutos
     *
     * @return void
     */
    public function getProdutos():void
    {
        $produtos = (new ProdutoModel())->find()->limit(10)->fetch(true);

        $json['message'] = 'Busca realizada com sucesso!';
        
        if(empty($produtos)){
            $json['message'] = 'Não encontramos nenhum resultado na busca!';
            $this->back($json);
            return;
        }

        foreach($produtos as $produto){
            $json['data'][] =  $produto->data();
        }

        $this->back($json);
        return;
    }

    /**
     * getProduto
     *
     * @param array $data
     * @return void
     */
    public function getProduto(array $data):void
    {
        $produto = (new ProdutoModel())->findById($data['id']);

        if(empty($produto)){
        
            $json['message'] = $this->message->warning('Desculpe, não encontramos o id informado')->render();    
            $this->back($json);
            return;
        }
        
        $json['message'] = $this->message->success('Usuário buscado com sucesso!')->render();   
        $json['data'][] = $produto->data();
        $this->back($json);
        return;
    }

    /**
     * postProduto
     *
     * @param array $data
     * @return void
     */
    public function postProduto(array $data): void
    {

        if(empty($data)){
            $json['message'] = $this->message->success('Nenhum dado informado')->render();
            $this->back($json);
            return;
        }

        $produto =  new ProdutoModel();
        $produto->fabricante_id = $data['fabricante_id'] ?? '';
        $produto->categoria_id = $data['categoria_id'] ?? '';
        $produto->nome =  $data['nome'] ?? '';
        $produto->preco = $data['preco'] ?? '';

      
        if(!$produto->save()){
            $json['message'] = $produto->message()->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('Produto cadastrado com sucesso!')->render();
        $json["data"] = $produto->data();
        $this->back($json);

        return;
    }

    /**
     * updateProduto
     *
     * @param array $data
     * @return void
     */
    public function updateProduto(array $data): void
    {
        if(empty($data)){
            $json['message'] = $this->message->success('Nenhum dado informado')->render();
            $this->back($json);
            return;
        }

        $produto =  (new ProdutoModel())->findById($data['id']);

        if(empty($produto)){
            $json['message'] = $this->message->success('Nenhum Produto foi encontrado')->render();
            $this->back($json);
            return;
        }

        $produto->fabricante_id = $data['fabricante_id'] ?? $produto->fabricante_id;
        $produto->categoria_id = $data['categoria_id'] ?? $produto->categoria_id;
        $produto->nome =  $data['nome'] ?? $produto->nome;
        $produto->preco = $data['preco'] ?? $produto->preco;

        if(!$produto->save()){
            $json['message'] = $produto->message()->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('User atualizado com sucesso!')->render();
        $json["data"] = $produto->data();
        $this->back($json);

        return;

    }

    /**
     * deleteUser
     *
     * @return void
     */
    public function deleteProduto(array $data): void
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

        $produto = new ProdutoModel();

        if(!$produto->findById($data['id'])){
            $json['message'] = $this->message->error('Não encontramos este Produto, por favor, contacte um administrador')->render();
            $this->back($json);
            return;
        }

        if(!$produto->delete('id', $data['id'])){
            $json['message'] = $this->message->error('Erro ao deletar Produto, por favor, contacte um adminstrador')->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('Produto deletado com sucesso!')->render();
        $this->back($json);
        return;
    }
}