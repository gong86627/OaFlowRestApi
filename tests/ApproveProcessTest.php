<?php

use gong86627\OaFlowRestApi\Business\ApproveProcess;
use PHPUnit\Framework\TestCase;

require '../vendor/autoload.php';
require './Config.php';

class ApproveProcessTest extends TestCase
{
    public function testDoRequest()
    {
        try{
            $client = new ApproveProcess(Config::$wsdl);
            $client->setParam('docSubject', '来自于接口的调用');
            $client->setParam('fdTemplateId', '1845a6e1c88ba11888755e74cbbb8cf9');
            $client->setParam('fdId', '187128b36ee290b974579ed48cfadf34');
            $client->setParam('docCreator', [
                "Id" => "185334d7cb27c53065d1e3a44758b143"
            ]);
            $client->setFlowParam('operationType', '通过');
            $client->setFlowParam('auditNote', '要得，就是这样干');
            $client->setFlowParam('changeNodeHandlers', ["N9" => ["185334d7cb27c53065d1e3a44758b143"]]);   //设置下一节点的处理人,可以不设置

            $res = $client->send();
            var_dump($res);
            $this->assertSame(true, $res['code'] == 0);
        }catch (Exception $e){
            var_dump($e->getCode(), $e->getMessage());
        }
    }
}