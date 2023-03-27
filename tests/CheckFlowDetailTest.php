<?php

use gong86627\OaFlowRestApi\Business\CheckFlowDetail;
use PHPUnit\Framework\TestCase;

require '../vendor/autoload.php';
require './Config.php';

class CheckFlowDetailTest extends TestCase
{
    public function testDoRequest()
    {
        try{
            $client = new CheckFlowDetail(Config::$baseUrl, Config::$userName, Config::$password);
            $client->setParam('fdId', "186b5c3a3eb2db4dd1e0d394993befe6");
            $res = $client->send();
            var_dump($res);
            $this->assertSame(true, $res['code'] == 0);
        }catch (Exception $e){
            var_dump($e->getCode(), $e->getMessage());
        }
    }
}