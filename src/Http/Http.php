<?php
namespace gong86627\OaFlowRestApi\Http;


use gong86627\OaFlowRestApi\ApiIO\ErrCode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
     * @param  string      $method
     * @param  string      $uri
     * @param  array|null  $params
     * @return string
     */
    protected static function request(string $method, string $uri, ?array $params): string
    {
        $client = new Client([
            'base_uri' => self::$baseUrl
        ]);

        try{
            $sendParams = [
                "auth" => [
                    self::$userName, self::$password
                ]
            ];
            $uri .= "?fdId=" . $params['fdId'];
            $response = $client->request($method, $uri, $sendParams);

            return $response->getBody()->getContents();
        }catch (GuzzleException $e){
            throw new Exception($e->getMessage(), ErrCode::NetErr);
        }
    }
}