<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Core\Request;
use Source\Models\Fabricante;
use Source\Models\Categoria;
use Source\Models\Produto;

class WebController extends Controller
{

    public function __construct($router)
    {
        parent::__construct(__DIR__."/../../themes/".CONF_VIEW_THEME."/", $router);
    }

    /**  */
    public function home()
    {

        $fabricantes = (new Fabricante())->find()->fetch(true);
        $categorias = (new Categoria())->find()->fetch(true);
        $produtos = (new Produto())->find()->fetch(true);

       echo $this->view->render('home', [
        'route' => $this->router,
        'fabricantes' => $fabricantes,
        'categorias' => $categorias,
        'produtos' => $produtos
       ]);
    }

    /**
     * cadastrar
     */
    public function cadastrar(array $data)
    {   



        //fabricante
        if(!empty($data['fabricante'])){
            $request = (new Request())
            ->headers(["X-Api-Key" => "5d41402abc4b2a76b9719d911017c592"])
            ->post('http://localhost/projeto/api/fabricante',["nome" => $data["fabricante"]])
            ->response();
            $fabricante_id = $request->data->id;
        }

       /*  var_dump($request);
        exit; */

        

        //categorias
        if(!empty($data['categoria_1']) || !empty($fabricante_id)){
            $request = (new Request())
            ->headers(["X-Api-Key" => "5d41402abc4b2a76b9719d911017c592"])
            ->post('http://localhost/projeto/api/categoria',["nome" => $data["categoria_1"],'fabricante_id' => $fabricante_id])
            ->response();
        }

    
        if(!empty($data['categoria_2']) || !empty($fabricante_id)){
            $request = (new Request())
            ->headers(["X-Api-Key" => "5d41402abc4b2a76b9719d911017c592"])
            ->post('http://localhost/projeto/api/categoria',["nome" => $data["categoria_2"],'fabricante_id' => $fabricante_id])
            ->response();
        }

        if(!empty($data['categoria_3']) || !empty($fabricante_id)){
            $request = (new Request())
            ->headers(["X-Api-Key" => "5d41402abc4b2a76b9719d911017c592"])
            ->post('http://localhost/projeto/api/categoria',["nome" => $data["categoria_3"], 'fabricante_id' => $fabricante_id])
            ->response();
        }


        //produto

        if(!empty($data['nome_produto'])){

            $produtoArray = [
                'nome' => $data['nome_produto'],
                'fabricante_id' => $data['fabricante_id'],
                'categoria_id' => $data['categoria_id'],
                'preco' => $data['preco']
            ];

            $request = (new Request())
            ->headers(["X-Api-Key" => "5d41402abc4b2a76b9719d911017c592"])
            ->post('http://localhost/projeto/api/produto',$produtoArray)
            ->response();
        }

        $json['message'] = 'Cadastro efetuado com sucesso!';
        echo json_encode($json);
        return;
    
    }
}