<?php

namespace Source\Models;

use Source\Core\Model;

class Posts extends Model
{
    public function __construct()
    {
        parent::__construct('posts',['id'],[]);
    }
}