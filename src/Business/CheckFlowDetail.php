<?php


namespace gong86627\OaFlowRestApi\Business;


use Exception;
use gong86627\OaFlowRestApi\ApiIO\ErrCode;
use gong86627\OaFlowRestApi\ApiIO\IO;
use gong86627\OaFlowRestApi\Http\Http;

class CheckFlowDetail extends Http
{
    protected string $fdId = "";
    protected string $uri  = "/api/km-review/instance/get";

    /**
     * ApproveProcess constructor.
     * @param $baseUrl
     * @param $userName
     * @param $password
     */
    public function __construct($baseUrl, $userName, $password)
    {
        parent::__construct($baseUrl, $userName, $password);
    }

    /**
     * set parameters
     * @param $name
     * @param $val
     * @throws Exception
     */
    public function setParam($name, $val)
    {
        if (property_exists($this, $name)) {
            $this->$name = $val;
        } else {
            throw new Exception($name.' is not defined');
        }
    }

    /**
     * request api
     * @return array
     * @throws Exception
     */
    public function send(): array
    {
        try {
            if(empty($this->fdId)){
                throw new Exception('fdId is required', ErrCode::ParamError);
            }
            $data = $this->request("POST", $this->uri, ["fdId" => $this->fdId]);

            if(!$data){
                throw new Exception('response data is Null', ErrCode::Business);
            }

            return IO::success(json_decode($data, true));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}