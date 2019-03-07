<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/8/18
 * Time: 12:05 AM
 */

use PHPUnit\Framework\TestCase;
use App\core\Route;
class RouteFunctionsTest extends TestCase
{
    /**
     * @var Route
     */
    private $router;

    private $testPath = '/test_path';
    private $testPathName = 'test.path';

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->router = Route::Instance();
        $this->router
            ->get($this->testPath,\App\core\BaseController::class,'test')
            ->name($this->testPathName);
    }

    /**
     * Test route::name() function
     */
    public function testNameFunction(){
        $this->assertEquals(
            1,
            count(Route::Instance()->getPool())
        );
    }

    /**
     * Test route::path($routeName) function
     */
    public function testPathFunction(){
        $this->assertEquals(
            url($this->testPath),
            $this->router->path($this->testPathName)
        );
    }

    /**
     * Test testMultiplePaths
     */
    public function testMultiplePaths(){
        foreach (range(0,10) as $index){
            $this->router
                ->get($this->testPath.$index,\App\core\BaseController::class,'test')
                ->name($this->testPathName.$index);

            $this->assertEquals(
                url($this->testPath.$index),
                $this->router->path($this->testPathName.$index)
            );
        }
    }

    public function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $this->router = null;
    }
}