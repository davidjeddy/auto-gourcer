<?php
declare(strict_types=1);

include_once '../vendor/autoload.php';

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
    protected $ag = null;

    public function setUp()
    {
        parent::setUp();

        $this->ag = new AutoGourcer();
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
        $this->ag->setRepoCount(1);
        $this->assertInstanceOf(AutoGourcer::class, $this->ag);
    }
}