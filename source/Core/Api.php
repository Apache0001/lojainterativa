<?php

namespace Source\Core;

use Source\Models\Auth;
use Source\Models\Key;
use Source\Support\Message;

class Api
{
    /** @var \Source\Models\User|null */
    protected $user;

    /** @var array|false */
    protected $headers;

    /** @var array|null */
    protected $response;


    /** @var \Source\Support\Message */
    protected $message;

    /**
     * Api constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        // configuracoes do header
        header('Content-Type: application/json; charset=UTF-8');
        //Pega todo o header na requisição.
        $this->headers = getallheaders();

        $this->message = new Message();

        $request = $this->requestLimit('Api',100, 60);
        if(!$request){
            exit;
        }

        $this->requestKey();

    }

    /**
     * @param int $code
     * @param string|null $type
     * @param string|null $message
     * @param string $rule
     * @return Api
     */
    protected function call(int $code, string $type = null, string $message = null, string $rule = "errors"): Api
    {
        http_response_code($code);

        if (!empty($type)) {
            $this->response = [
                $rule => [
                    "type" => $type,
                    "message" => (!empty($message) ? $message : null)
                ]
            ];
        }
        return $this;
    }

    /**
     * @param array|null $response
     * @return Api
     */
    protected function back(array $response = null): Api
    {
        if (!empty($response)) {
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * @return bool
     */
    protected function auth(): bool
    {   
        //define os parâmetros máximo do request
        //$endpoint = ["apiAuth", 3, 60];
        //define o máximo de request por tempo
        //$request = $this->requestLimit($endpoint[0], $endpoint[1], $endpoint[2], true);

        //verifica se o request foi setado.
        // (!$request) {
           // return false;
        //}
        
        // Verifica se os dados email e password estão vindo no cabecalho, caso contrário, dá erro auth_empty
        if (empty($this->headers["email"]) || empty($this->headers["password"])) {
            $this->call(
                400,
                "auth_empty",
                "Favor informe seu e-mail e senha"
            )->back();
            return false;
        }

        // se os dados estão setados no cabecalho, ora de verificar se estão de acordo.
        $auth = new Auth();
        $user = $auth->verify($this->headers["email"], $this->headers["password"], 1);//verifica no banco de dados os dados

        if (!$user) {
            $this->requestLimit($endpoint[0], $endpoint[1], $endpoint[2]);
            $this->call(
                401,
                "invalid_auth",
                $auth->message()->getText()
            )->back();
            return false;
        }

        $this->user = $user;
        return true;
    }

    /**
     * @param string $endpoint
     * @param int $limit
     * @param int $seconds
     * @param bool $attempt
     * @return bool
     */
    protected function requestLimit(string $endpoint, int $limit, int $seconds, bool $attempt = false): bool
    {

        //$userToken = (!empty($this->headers["email"]) ? base64_encode($this->headers["email"]) : null);
        $userToken = base64_encode($_SERVER["REMOTE_ADDR"]);

         if (!$userToken) {
            $this->call(
                400,
                "invalid_data",
                "Ip de origem não informado."
            )->back();

            return false;
        } 

        $cacheDir = __DIR__ . "/../../" . CONF_UPLOAD_DIR . "/requests";
        if (!file_exists($cacheDir) || !is_dir($cacheDir)) {
            mkdir($cacheDir, 0755);
        }

        $cacheFile = "{$cacheDir}/{$userToken}.json";
        if (!file_exists($cacheFile) || !is_file($cacheFile)) {
            fopen($cacheFile, "w");
        }

        $userCache = json_decode(file_get_contents($cacheFile));
        $cache = (array)$userCache;

        $save = function ($cacheFile, $cache) {
            $saveCache = fopen($cacheFile, "w");
            fwrite($saveCache, json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fclose($saveCache);
        };

        if (empty($cache[$endpoint]) || $cache[$endpoint]->time <= time()) {
            if (!$attempt) {
                $cache[$endpoint] = [
                    "limit" => $limit,
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "requests" => 1,
                    "time" => time() + $seconds,
                    
                ];

                $save($cacheFile, $cache);
            }

            return true;
        }

        if ($cache[$endpoint]->requests >= $limit) {
            $this->call(
                400,
                "request_limit",
                "Você exedeu o limite de requisições para essa ação"
            )->back();
            
            return false;
        }

        if (!$attempt) {
            $cache[$endpoint] = [
                "limit" => $limit,
                "ip" => $_SERVER["REMOTE_ADDR"],
                "requests" => $cache[$endpoint]->requests + 1,
                "time" => $cache[$endpoint]->time
            ];

            $save($cacheFile, $cache);
        }
        return true;
    }
    
    /**
     * requestKey: verifica se a requisição possue a chave key e se não existir encerra a exceção.
     *
     * @return void
     */
    public function requestKey()
    {
       /*  if(empty($this->headers['Key'])){
            $this->call(
                401,
                "request_key",
                "Key de acesso não informada."
            )->back();
            exit;
        }

        $key = new Key();
        
        if(!$key->verifyKey($this->headers["Key"])){
            $this->call(
                401,
                "request_key",
                "Key de acesso inválida."
            )->back();
            exit;
        } */
    }

}