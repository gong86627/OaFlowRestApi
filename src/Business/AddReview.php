<?php

namespace gong86627\OaFlowRestApi\Business;

use Exception;
use gong86627\OaFlowRestApi\ApiIO\RestErrCode;
use gong86627\OaFlowRestApi\ApiIO\RestIO;

/**
 * 启动流程
 * Class AddReview
 * @package gong86627\OaFlowRestApi\Business
 */
class AddReview extends CommonService
{
    protected ?array $formValues;                       //流程表单数据，允许为空

    /**
     * initializes
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
            ['code' => $code, 'data' => $data] = $this->getClient()->execute("addReview", $params);
            if ($code != 0) {
                throw new Exception('No result response!', RestErrCode::NetErr);
            }
            return RestIO::success($data);
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
        if (empty($this->docCreator) || empty($this->docSubject) || empty($this->fdTemplateId) || empty($this->formValues)) {
            throw new Exception('You must specify', RestErrCode::ParamEmpty);
        }

        $body = [
            'docCreator'   => json_encode($this->docCreator),
            'docSubject'   => $this->docSubject,
            'fdTemplateId' => $this->fdTemplateId,
            "formValues"   => json_encode($this->formValues),
        ];

        if ($this->attachmentForms) {
            $body['attachmentForms'] = $this->attachmentForms;
        }

        if($this->flowParam){
            $body["flowParam"] = json_encode($this->flowParam);
        }

        return ['arg0' => $body];
    }
}