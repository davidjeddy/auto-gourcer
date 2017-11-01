<?php
declare(strict_types=1);
namespace dje\AutoGourcer\Tests;

use \PHPUnit\Framework\TestCase;
use \dje\AutoGourcer\AutoGourcer;

/**
 * Class AutoGourcerTestTest
 *
 * @source https://jtreminio.com/2013/03/unit-testing-tutorial-introduction-to-phpunit/ Thank you!!!
 * @package tests
 */
class AutoGourcerTest extends TestCase
{
    /**
     * @var null
     */
    protected $autoGourcer = null;

    public function setUp()
    {
        parent::setUp();

        $this->autoGourcer = new AutoGourcer();
    }

    /**
     *
     */
    public function testTrueIsTrue()
    {
        $foo = true;
        $this->assertTrue($foo);
    }

    public function testClassInstanceOfAfterSetRepoCountMethod()
    {
        $this->autoGourcer->setRepoCount(1);
        $this->assertInstanceOf(AutoGourcer::class, $this->autoGourcer);
    }
}
