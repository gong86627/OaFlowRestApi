<?php


namespace gong86627\OaFlowRestApi\Business;


use gong86627\OaFlowRestApi\ApiIO\ErrCode;
use Exception;
use gong86627\OaFlowRestApi\ApiIO\IO;

/**
 * 更新流程
 * Class ApproveProcess
 * @package gong86627\OaFlowRestApi\Business
 */
class UpdateReviewInfo extends CommonService
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
            ['code' => $code, 'data' => $data] = $this->getClient()->execute("updateReviewInfo", $params);
            if ($code != 0) {
                throw new Exception('No result response!', ErrCode::NetErr);
            }
            return IO::success($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * set params for request
     * @return array
     * @throws Exception
     */
    protected function processSendParams(): array
    {
        if (empty($this->fdId) || empty($this->docCreator) || empty($this->docSubject) || empty($this->fdTemplateId) || empty($this->formValues)) {
            throw new Exception('You must specify', ErrCode::ParamEmpty);
        }

        $body = [
            "fdId"         => $this->fdId,
            "docCreator"   => json_encode($this->docCreator),
            "docSubject"   => $this->docSubject,
            "fdTemplateId" => $this->fdTemplateId,
            "formValues"   => json_encode($this->formValues),
        ];

        if($this->flowParam){
            $body["flowParam"] = json_encode($this->flowParam);
        }

        if ($this->attachmentForms) {
            $body['attachmentForms'] = $this->attachmentForms;
        }

        return ['arg0' => $body];
    }
}