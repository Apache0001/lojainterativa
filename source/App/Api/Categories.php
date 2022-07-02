<?php

namespace Source\App\Api;

use Source\Core\Api;
use Source\Models\Categories as CategoriesModel;

class Categories extends Api
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
    public function getCategories(): void
    {
        $categories = (new CategoriesModel())->find()->fetch(true);

        if(!$categories){
            $json[] = $this->message->warning('Não encontramos nenhum resultado para essa busca')->render();
            $this->back($json);
            return;
        }

        foreach($categories as $category){
            $json[] = $category->data();
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
    public function getCategory(array $data)
    {
        $data = filter_var_array($data);

        $category = (new CategoriesModel())->find("id = :id", "id={$data['id']}")->fetch();

        if(!$category){
            $json[] = $this->message->warning('Não encontramos nenhum resultado para essa busca')->render();
            $this->back($json);
            return;
        }
        $json[] = $category->data();
        $this->back($json);
        return;
    }
}