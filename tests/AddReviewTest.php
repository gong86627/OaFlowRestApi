<?php

use PHPUnit\Framework\TestCase;
use gong86627\OaFlowRestApi\Business\AddReview;

require '../vendor/autoload.php';
require './Config.php';

class AddReviewTest extends TestCase
{
    public function testDoRequest()
    {
        try{
            $client = new AddReview(Config::$wsdl);
            $client->setParam('docSubject', '这是一个测试来自于WebService');
            $client->setParam('fdTemplateId', '1845a6e1c88ba11888755e74cbbb8cf9');
            $client->setParam('docCreator', [
                "Id" => "185334d7cb27c53065d1e3a44758b143"
            ]);
            $client->setParam('formValues', [
                "fd_3b9bb90d2c22a2" => "投诉信箱",
                "fd_3b422ead8f3d98" => "12341234"
            ]);
            $client->setParam('attachmentForms', [
                [
                    "fdKey" => "fd_3b420d5dac199e",
                    "fdFileName" => "1.png",
                    "fdAttachment" => file_get_contents('1.png'),
                ]
            ]);
            $res = $client->send();
            var_dump($res);
            $this->assertSame(true, $res['code'] == 0);
        }catch (Exception $e){
            var_dump($e->getCode(), $e->getMessage());
        }
    }
}