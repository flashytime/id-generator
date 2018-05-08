<?php
/**
 * Created by IntelliJ IDEA.
 * Author: flashytime
 * Date: 2015/3/6 20:42
 */

namespace Flashytime\IdGenerator\Tests;

use Flashytime\IdGenerator\IdGenerator;

class IdGeneratorTest extends \PHPUnit\Framework\TestCase
{
    public function testGetId()
    {
        $config = $this->getConfig();
        $idGenerator = new IdGenerator($config);
        $idGenerator->setTable('id_generator');
        $this->assertInternalType('integer', $idGenerator->getId('test_name'));
    }

    public function getConfig()
    {
        return require __DIR__ . '/../src/config/id-generator.php';
    }
}