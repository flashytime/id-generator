<?php
/**
 * Created by IntelliJ IDEA.
 * Author: flashytime
 * Date: 2015/2/26 21:08
 */

namespace Flashytime\IdGenerator;

use PDO;
use Exception;

class Db
{
    /**
     * @var PDO
     */
    private $connection;
    private $table;
    private $cache;
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->cache = new Cache();
    }

    /**
     * @param $name
     * @param $step
     * @param $length
     * @param $originId
     * @return int|mixed
     * @throws Exception
     */
    public function getId($name, $step, $length, $originId)
    {
        try {
            $this->getConnection()->beginTransaction();
            $sql = "SELECT `current_id` FROM {$this->getTable()} WHERE `name` = :name FOR UPDATE";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue('name', $name);
            $stmt->execute();
            $currentId = $stmt->fetch(PDO::FETCH_COLUMN);
            if ($currentId === false) {
                $startId = $originId;
                $currentId = $startId + $step * $length;
                $this->create($name, $currentId, $step, $length);
            } else {
                $startId = $currentId + $step;
                $currentId += $step * $length;
                $this->update($name, $currentId);
            }
            $this->getCache()->saveIds($name, $startId, $currentId);
            $this->getConnection()->commit();
        } catch (Exception $e) {
            $this->getConnection()->rollBack();
            throw $e;
        }

        return $startId;
    }

    public function getConnection()
    {
        if (!$this->connection) {
            $dbConfig = $this->config['database'];
            $host = $dbConfig['host'];
            $port = $dbConfig['port'];
            $database = $dbConfig['database'];
            $user = $dbConfig['user'];
            $password = $dbConfig['password'];

            $this->connection = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database, $user,
                $password);
        }

        return $this->connection;
    }

    private function create($name, $currentId, $step, $length)
    {
        $sql = "INSERT {$this->getTable()} (`name`, `current_id`, `step`, `length`) VALUES (:name, :currentId, :step, :length)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':currentId', $currentId);
        $stmt->bindValue(':step', $step);
        $stmt->bindValue(':length', $length);
        $stmt->execute();
    }

    private function update($name, $currentId)
    {
        $sql = "UPDATE {$this->getTable()} SET `current_id` = :currentId WHERE `name` = :name";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue(':currentId', $currentId);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }
}
