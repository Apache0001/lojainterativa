<?php

namespace Source\Core;

use Source\Support\Message;

/**
 * FSPHP | Class Model Layer Supertype Pattern
 *
 * @author Pablo O. Mesquita
 * @package Source\Models
 */
abstract class Model
{
    /** @var object|null */
    protected $data;

    /** @var \PDOException|null */
    protected $fail;

    /** @var Message|null */
    protected $message;

    /** @var string $entity database table */
    protected static $entity;

    /** @var array $protected no update or create */
    protected static $protected;

    /** @var array $entity database table */
    protected static $required;

    /** @var string */
    protected $query;

    /** @var array */
    protected $params;

    /** @var string */
    protected $order;

    /** @var null|int */
    protected $limit;

    /** @var null|int */
    protected $offset;

    /**
     * Model constructor.
     * @param string $entity database table name
     * @param array $protected table protected columns
     * @param array $required table required columns
     */

    public function __construct(string $entity, array $protected, array $required)
    {
        self::$entity = $entity;
        self::$protected = array_merge($protected, ['date_added', "date_modified"]);
        self::$required = $required;
        $this->message = new Message();
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @param $name
     * @return null|string 
     */
    
    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }
    

    /**
     * @return null|object
     */
    public function data(): ?object
    {
        return $this->data;
    } 

    /**
     * @return \PDOException
     */
    public function fail(): ?\PDOException
    {
        return $this->fail;
    }

    /**
     * @return Message|null
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * @param null|string $terms
     * @param null|string $params
     * @param string $columns
     * @return Model|mixed
     */

    public function find(?string $terms = null, ?string $params = null, string $columns = '*') 
    {
       
        if($terms){
            $this->query = "SELECT {$columns} FROM ".static::$entity." WHERE {$terms} ";
            parse_str($params, $this->params);
            return $this;
        }

        $this->query = "SELECT {$columns} FROM ".static::$entity ;
        return $this;
    }

    /**
     * @param int $id
     * @param string $columns
     * @return null|Model|mixed
     */
    public function findById(int $id, string $columns = "*"): ?Model
    {
        $find =  $this->find("id = :id", "id={$id}", $columns);

        return $find->fetch();
    }

    public function order(?string $columOrder): Model
    {
        $this->order = " ORDER BY {$columOrder} ";
        return $this;
    }

    public function limit(?int $limit): Model
    {
        if($limit){
            $this->limit = " LIMIT {$limit}";
        }
       
        return $this;
    }

    /**
     * @var int $offset
     * @return Model
     */

    public function offset(?int $offset): Model
    {
        if($offset){
            $this->offset = " OFFSET {$offset} ";
        }
       
        return $this;
    }

    /**
     * Método executa a query
     * @param bool $all
     * @return null|array|mixed|
     */

    public function fetch(bool $all = false)
    {
        
        try{
            
            //Motando a query
            $stmt = Connect::getInstance()->prepare($this->query . $this->order. $this->limit  . $this->offset );
            // Executand
            $stmt->execute($this->params);
            if(!$stmt->rowCount()){
                
                return null;
            }
          
            if($all){
                return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
            }
           
            return $stmt->fetchObject(static::class);

            

        }catch(\PDOException $exception){
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * Método retornar o número de objetos retornados em uma busca
     * @return int
     */

    public function count(): int
    {
        $stmt = Connect::getInstance()->prepare($this->query);
        $stmt->execute($this->params);

        return $stmt->rowCount();
    }

    // ############################################################# CRUD #####################################################

    /**
     * @param array $data
     * @return int|null
     */
    protected function create(array $data): ?int
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
          
            $stmt = Connect::getInstance()->prepare("INSERT INTO " . static::$entity . " ({$columns}) VALUES ({$values})");

            $stmt->execute($this->filter($data));

            return Connect::getInstance()->lastInsertId();

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $select
     * @param string|null $params
     * @return null|\PDOStatement
     */
    protected function read(string $select, string $params = null): ?\PDOStatement
    {
        try {
            $stmt = Connect::getInstance()->prepare($select);
            if ($params) {
                parse_str($params, $paramss);
                foreach ($paramss as $key => $value) {
                    if ($key == 'limit' || $key == 'offset') {
                        $stmt->bindValue(":{$key}", $value, \PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":{$key}", $value, \PDO::PARAM_STR);
                    }
                }
            }

            $stmt->execute();
            return $stmt;
            
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $entity
     * @param array $data
     * @param string $terms
     * @param string $params
     * @return int|null
     */
    protected function update (array $data, string $terms, string $params): ?int
    {
        try {
            $dateSet = [];
            foreach ($data as $bind => $value) {
                $dateSet[] = "{$bind} = :{$bind}";
            }
            $dateSet = implode(", ", $dateSet);
            parse_str($params, $paramss);

            $stmt = Connect::getInstance()->prepare("UPDATE ".static::$entity . " SET {$dateSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data, $paramss)));
            return ($stmt->rowCount() ?? 1);
            
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

        
    /**
     * delete
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return bool
     */
    public function delete(string $key, string $value): ?bool
    {
        try {
            $stmt = Connect::getInstance()->prepare("DELETE FROM ".static::$entity." WHERE {$key} = :key ");
            $stmt->bindValue("key", $value, \PDO::PARAM_STR);
            $stmt->execute();

            return true;

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return false;
        }
    }

   /**
    * save
    *
    * @return boolean
    */
     public function save(): bool
     {

         /* if(!$this->required()){
             $this->message->warning('Preencha todos os campos para continuar');
             return false;
         } */

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
     }

    /**
     * @return array|null
     */
    protected function safe(): ?array
    {
        // retira os indices protegidos dos dados.
        $safe = (array)$this->data;
        foreach (static::$protected as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    /**
     * @param array $data
     * @return array|null
     */

    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }

    /**
     * @return bool
     */
    protected function required(): bool
    {
        $data = (array)$this->data();
        foreach (static::$required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }
}