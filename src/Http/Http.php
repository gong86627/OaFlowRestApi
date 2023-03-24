<?php
namespace gong86627\OaFlowRestApi\Http;


use gong86627\OaFlowRestApi\ApiIO\ErrCode;
use gong86627\OaFlowRestApi\ApiIO\IO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use PHPUnit\Util\Exception;

class Http
{
    private static string $baseUrl  = "";
    private static string $userName = "";
    private static string $password = "";

    /**
     * Http constructor.
     * @param $baseUrl
     * @param $userName
     * @param $password
     */
    public function __construct($baseUrl, $userName, $password)
    {
        self::$baseUrl  = $baseUrl;
        self::$userName = $userName;
        self::$password = $password;
    }

    /**
     * 发起请求
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $params
     * @return string
     */
    protected static function request(string $method, string $uri, array $params): string
    {
        $client = new Client([
            'base_uri' => self::$baseUrl
        ]);
        try{
            $sendParams = [
                'auth' => [
                    self::$userName, self::$password
                ]
            ];
            if(isset($params[RequestOptions::MULTIPART]) && $params[RequestOptions::MULTIPART]){
                $sendParams[RequestOptions::MULTIPART] = $params[RequestOptions::MULTIPART];
            }else{
                $sendParams[RequestOptions::FORM_PARAMS] = $params;
            }

            $response = $client->request($method, $uri, $sendParams);
            return $response->getBody()->getContents();
        }catch (GuzzleException $e){
            throw new Exception($e->getMessage(), ErrCode::NetErr);
        }
    }
}