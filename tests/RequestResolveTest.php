<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\Request;
use Peak\Routing\RequestResolve;
use Peak\Routing\Route;
use Peak\Routing\Regex;

/**
 * @package    Peak\Resolve
 */
class RequestResolveTest extends TestCase
{
    /**
     * init view
     */ 
    function setUp()
    {
        //$this->peakview = new Peak\View();
    }
    
    /**
     * unset view
     */
    function tearDown()
    {
        unset($this->peakview);
    }

    function testEmptyRequest()
    {
        $base_uri = '';
        $request_uri = '';
        $request = new Request($request_uri, $base_uri);

        $this->assertEmpty($request->raw_uri);  
        $this->assertTrue($request->request_uri === '/');  
        $this->assertTrue($request->base_uri === '/'); 

        $base_uri = 'base';
        $request_uri = '';
        $request = new Request($request_uri, $base_uri);

        $this->assertEmpty($request->raw_uri);  
        $this->assertTrue($request->request_uri === '/');  
        $this->assertTrue($request->base_uri === '/base/'); 
    }
    
    /**
     * Create instance test
     */
    function testRequest()
    {

        Request::$separator = '/';

        $base = 'peak/framework';
        $request = 'peak/framework/asdasd';
        $request = new Request($request, $base);

        // echo "\n";
        // echo $request->base_uri;
        // echo "\n";
        // echo $request->raw_uri;
        // echo "\n";
        // echo $request->request_uri;
        // echo "\n";

        $resolver = new RequestResolve($request);

        $reg = new Regex('{id}:num', 'index', 'action');
        //print_r($reg);
        //print_r($reg->match($request));

        $reg2 = new Regex(':alpha', 'module', 'action');
        //print_r($reg2->match($request));


        //print_r($resolver->getRoute());
    }
}