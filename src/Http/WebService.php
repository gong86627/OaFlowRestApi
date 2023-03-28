<?php


namespace gong86627\OaFlowRestApi\Http;


use Exception;
use gong86627\OaFlowRestApi\ApiIO\RestErrCode;
use gong86627\OaFlowRestApi\ApiIO\RestIO;
use SoapClient;
use SoapHeader;

class WebService
{
    protected string $wsdl = "";                           //地址
    protected ?SoapClient $soapClient  = null;           //请求客户端

    /**
     * @return $this
     * @throws Exception
     */
    protected function getClient(): WebService
    {
        try {
            $this->soapClient = new SoapClient($this->wsdl);
            $header = new SoapHeader("http://sys.webservice.client", 'RequestSOAPHeader');
            $this->soapClient->__setSoapHeaders($header);
            return $this;
        } catch (\SoapFault $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $serviceBean
     * @param $params
     * @return array
     * @throws Exception
     */
    protected function execute($serviceBean, $params): array
    {
        if(empty($this->soapClient)) throw new Exception('No soap client', RestErrCode::Business);
        $soapRerun = $this->soapClient->$serviceBean($params);
        $fdId = $soapRerun->return ?? null;
        if(empty($fdId)) throw new Exception('return null for soap', RestErrCode::Business);
        return RestIO::success(['fdId' => $fdId]);
    }
}