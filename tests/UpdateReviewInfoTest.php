<?php

use gong86627\OaFlowRestApi\Business\UpdateReviewInfo;
use PHPUnit\Framework\TestCase;

require '../vendor/autoload.php';
require './Config.php';

class UpdateReviewInfoTest extends TestCase
{
    public function testDoRequest()
    {
        try{
            $client = new UpdateReviewInfo(Config::$wsdl);
            $client->setParam('docSubject', '随便改一下东西');
            $client->setParam('fdTemplateId', '1845a6e1c88ba11888755e74cbbb8cf9');
            $client->setParam('fdId', '187128b36ee290b974579ed48cfadf34');
            $client->setParam('docCreator', [
                "Id" => "185334d7cb27c53065d1e3a44758b143"
            ]);
            $client->setParam('formValues', [
                "fd_3b9bb90d2c22a2" => "投诉信箱",
                "fd_3b422ead8f3d98" => "我就是我，我就是爱音乐"
            ]);
            $client->setParam('attachmentForms', [
                [
                    "fdKey" => "fd_3b420d5dac199e",
                    "fdFileName" => "2.mp4",
                    "fdAttachment" => file_get_contents('2.mp4'),
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