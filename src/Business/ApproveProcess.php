<?php


namespace gong86627\OaFlowRestApi\Business;


use gong86627\OaFlowRestApi\ApiIO\RestErrCode;
use Exception;
use gong86627\OaFlowRestApi\ApiIO\RestIO;

/**
 * 审批流程
 * Class ApproveProcess
 * @package gong86627\OaFlowRestApi\Business
 */
class ApproveProcess extends CommonService
{
    /**
     * ApproveProcess constructor.
     * @param $wsdl
     */
    public function __construct($wsdl)
    {
        $this->setWsdl($wsdl);
    }

    /**
     * request api
     * @return array
     * @throws Exception
     */
    public function send(): array
    {
        try {
            $params = $this->processSendParams();
            ['code' => $code, 'data' => $data] = $this->getClient()->execute("approveProcess", $params);
            if ($code != 0) {
                throw new Exception('No result response!', RestErrCode::NetErr);
            }
            return RestIO::success($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}