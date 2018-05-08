<?php
/**
 * Created by IntelliJ IDEA.
 * Author: flashytime
 * Date: 2015/2/26 21:08
 */

namespace Flashytime\IdGenerator;

class Cache
{
    const CACHE_KEY_CURRENT_ID_PREFIX = 'CACHE_KEY_CURRENT_ID';
    const CACHE_KEY_MAX_ID_PREFIX = 'CACHE_KEY_MAX_ID';
    const CACHE_KEY_LOCK_PREFIX = 'CACHE_KEY_LOCK';

    /**
     * @param $name
     * @param $step
     * @return bool|int|string
     */
    public function getId($name, $step)
    {
        $currentIdCacheKey = $this->getCurrentIdCacheKey($name);
        $currentId = $this->fetch($currentIdCacheKey);
        $maxId = $this->fetch($this->getMaxIdCacheKey($name));
        if ($currentId === false || $maxId === false) {
            return false;
        }

        $currentId += (int)$step;
        $this->store($currentIdCacheKey, $currentId);

        if ($currentId <= $maxId) {
            return $currentId;
        }

        return false;
    }

    /**
     * @param $name
     * @param $currentId
     * @param $maxId
     */
    public function saveIds($name, $currentId, $maxId)
    {
        $this->store($this->getCurrentIdCacheKey($name), $currentId);
        $this->store($this->getMaxIdCacheKey($name), $maxId);
    }

    /**
     * @param $name
     */
    public function lock($name)
    {
        $this->add($this->getLockCacheKey($name), 1, 1);
    }

    /**
     * @param $name
     */
    public function unLock($name)
    {
        $this->delete($this->getLockCacheKey($name));
    }

    public function add($key, $var, $ttl = 0)
    {
        return apcu_add($key, $var, $ttl);
    }

    public function delete($key)
    {
        return apcu_delete($key);
    }

    public function fetch($key, &$success = null)
    {
        return apcu_fetch($key, $success);
    }

    public function store($key, $var, $ttl = 0)
    {
        return apcu_store($key, $var, $ttl);
    }

    private function getCurrentIdCacheKey($name)
    {
        return self::CACHE_KEY_CURRENT_ID_PREFIX . ':' . $name;
    }

    private function getMaxIdCacheKey($name)
    {
        return self::CACHE_KEY_MAX_ID_PREFIX . ':' . $name;
    }

    private function getLockCacheKey($name)
    {
        return self::CACHE_KEY_LOCK_PREFIX . ':' . $name;
    }
}
