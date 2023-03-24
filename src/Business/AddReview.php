<?php

namespace gong86627\OaFlowRestApi\Business;

use Exception;
use gong86627\OaFlowRestApi\ApiIO\ErrCode;
use gong86627\OaFlowRestApi\ApiIO\IO;
use gong86627\OaFlowRestApi\Http\WebService;
use PHPUnit\Util\Json;

class AddReview extends WebService
{
    protected string $wsdl       = "";
    protected string $docSubject;                       //文档标题，不允许为空
    protected string $fdTemplateId;                     //文档模板id，不允许为空
    protected string $docContent = "";                  //文档的富文本内容
    protected ?array $formValues;                       //流程表单数据，允许为空
    protected string $docStatus  = "";                  //文档状态，可以为草稿（"10"）或者待审（"20"）两种状态，默认为待审
    protected ?array $docCreator;                       //流程发起人，为单值，格式详见人员组织架构的定义说明 ，不允许为空
    protected ?array $fdKeyword;                        //文档关键字，格式为["关键字1", "关键字2"...]，允许为空
    protected ?array $docProperty;                      //辅类别，格式为["辅类别1的ID", "辅类别2的ID"...]，允许为空
    protected ?array $flowParam;                        //流程参数，允许为空
    protected ?array $attachmentForms;                  //附件列表，允许为空

    /**
     * initializes
     * @param $wsdl
     */
    public function __construct($wsdl)
    {
        $this->setWsdl($wsdl);
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
            $params = $this->processSendParams();
            ['code' => $code, 'data' => $data] = $this->getClient()->execute("addReview", $params);
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
        if (empty($this->docCreator) || empty($this->docSubject) || empty($this->fdTemplateId) || empty($this->formValues)) {
            throw new Exception('You must specify', ErrCode::ParamEmpty);
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

        return ['arg0' => $body];
    }
}