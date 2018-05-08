<?php
/**
 * Created by IntelliJ IDEA.
 * Author: flashytime
 * Date: 2015/2/26 15:59
 */

namespace Flashytime\IdGenerator;

use Exception;

class IdGenerator
{
    const MAX_RETRY_COUNT = 5;

    private $cache;
    private $db;
    private $config;

    public function __construct($config)
    {
        $this->cache = new Cache();
        $this->db = new Db($config);
    }

    /**
     * @param $name
     * @param int $step
     * @param int $length
     * @param int $originId
     * @return bool|int|mixed|string
     * @throws Exception
     */
    public function getId($name, $step = 1, $length = 100, $originId = 10000)
    {
        $retryCount = 0;
        while (true) {
            if ($this->getCache()->lock($name) === false) {
                if ($retryCount <= self::MAX_RETRY_COUNT) {
                    $retryCount++;
                } else {
                    throw new Exception('Apc Error!');
                }
                usleep(100000);
                continue;
            }

            $id = $this->getCache()->getId($name, $step);
            if ($id === false) {
                $id = $this->getDb()->getId($name, $step, $length, $originId);
            }
            $this->getCache()->unLock($name);
            break;
        }

        return $id;
    }

    /**
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return Db
     */
    public function getDb()
    {
        return $this->db;
    }

    public function setTable($table)
    {
        $this->getDb()->setTable($table);
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
