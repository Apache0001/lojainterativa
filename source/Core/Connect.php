<?php

namespace Source\Core;

use PDOException;

/**
 * Class Connect [ Single Pattern ]
 * 
 * @author Pablo O.Mesquita <pablomesquita.agv@gmail.com>
 * @package Source\Core
 */
class Connect
{
    /** @const array */
    private const OPTIONS = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL
    ];

    /** @var \PDO */
    private static $instance;


    /** @return \PDO */
    public static function getInstance(): ?\PDO
    {
        if(empty(self::$instance)){
            try{
                self::$instance =  new \PDO(
                    "mysql:host=".CONF_DB_HOST.";dbname=".CONF_DB_NAME,
                    CONF_DB_USER,
                    CONF_DB_PASSWD,
                    self::OPTIONS
                );

            }catch(PDOException $exception){
                var_dump($exception);
            }
        }

        return self::$instance;
    }

    /**
     * Connect clone.
     */
    final private function __clone()
    {
    }
}