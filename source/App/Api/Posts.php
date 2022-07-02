<?php

namespace Source\App\Api;

use Source\Core\Api;
use Source\Models\Posts as PostModel;

class Posts extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getPosts
     *
     * @return void
     */
    public function getPosts(): void
    {
        $posts = (new PostModel())->find()->fetch(true);

        foreach($posts as $post){
            $json[] = $post->data();
        }

        $this->back($json);

        return;

    }

    /**
     * getPost
     *
     * @param array|null $data
     * @return void
     */
    public function getPost(?array $data):void
    {
        $data = filter_var_array($data);

        $response = [];

        $post = (new PostModel())->find("id = :id", "id={$data['id']}")->fetch();

        if(empty($post)){
            $response[] = $this->message->warning('Desculpe, não encontramos o id informado')->render();    
            $json[] = $response;
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('Post buscado com sucesso!')->render();
        $json['post'] = $post->data();

        $this->back($json);

        return;
    }

    /**
     * postPost
     *
     * @param array|null $data
     * @return void
     */
    public function postPost(?array $data): void
    {
        $post =  new PostModel();
        $post->author = $data['author'] ?? '';
        $post->category =  $data['category'] ?? '';
        $post->title = $data["title"] ?? '';
        $post->uri = $data['uri'] ?? '';
        $post->subtitle = $data['subtitle'] ?? '';
        $post->content = $data['content'] ?? '';
        $post->cover = $data['cover'] ?? '';
        $post->video = $data['video'] ?? '';
        $post->views = $data['views'] ?? '0';
        $post->status = $data['status'] ?? 'post';
        $post->post_at = $data['post_at'] ?? null;
        //$post->created_at = date_fmt_app();
        //$post->updated_at = date_fmt_app();
        $post->deleted_at = $data['deleted_at'] ?? null;
       
        if(!$post->save()){
            $json[] = $post->fail();
            $this->back($json);
            return;
        }


        $json['message'] = $this->message->success('Post cadastrado com sucesso!')->render();
        $json["data"][] = $post->data();
        $this->back($json);

        return;
    }

    /**
     * delete
     *
     * @param array $data
     * @return void
     */
    public function deletePost(array $data)
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

        $post = new PostModel();

        if(!$post->findById($data['id'])){
            $json['message'] = $this->message->error('Não encontramos este post, por favor, contacte um administrador')->render();
            $this->back($json);
            return;
        }

        if(!$post->delete('id', $data['id'])){
            $json['message'] = $this->message->error('Erro ao deletar posts, por favor, contacte um adminstrador')->render();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('Post deletado com sucesso!')->render();
        $this->back($json);
        return;

    }

    /**
     * updatePost
     *
     * @param array $data
     * @return void
     */
    public function updatePost(array $data)
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

        $post = new PostModel();

        if(!$post = $post->findById($data['id'])){
            $json['message'] = $this->message->error('Não encontramos este post, por favor, contacte um administrador')->render();
            $this->back($json);
            return;
        }

        $post->author = $data['author'] ?? $post->author;
        $post->category =  $data['category'] ?? $post->category;
        $post->title = $data["title"] ?? $post->title;
        $post->uri = $data['uri'] ?? $post->uri;
        $post->subtitle = $data['subtitle'] ?? $post->subtitle;
        $post->content = $data['content'] ?? $post->content;
        $post->cover = $data['cover'] ?? $post->cover;
        $post->video = $data['video'] ?? $post->video;
        $post->views = $data['views'] ?? $post->views;
        $post->status = $data['status'] ?? $post->status;
        $post->post_at = $data['post_at'] ?? $post->post_at;
        $post->created_at = $post->created_at;
        $post->updated_at = date_fmt_app();
        $post->deleted_at = $data['deleted_at'] ?? $post->deleted_at;

        if(!$post->save()){
            $json[] = $post->message();
            $this->back($json);
            return;
        }

        $json['message'] = $this->message->success('Post atualizado com sucesso!')->render();
        $this->back($json);
        return;

    }



}