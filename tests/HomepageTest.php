<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/8/18
 * Time: 3:53 PM
 */
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

class HomepageTest extends TestCase
{
    /**
     * @var Client
     */
    private $http;

    /**
     * @var Dom
     */
    private $domParser;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->http = new Client(
            [
                'base_uri' => env('SITE_URL')
            ]
        );
        $this->domParser = new Dom;
    }

    /**
     * 测试网站首页是否可以正确渲染
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetHomepage(){
        $response = $this->http->request('GET', '/');
        // Make sure website is on
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function tearDown() {
        $this->http = null;
        $this->domParser = null;
    }

}