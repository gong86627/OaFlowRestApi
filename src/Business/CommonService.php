<?php


namespace gong86627\OaFlowRestApi\Business;

use Exception;
use gong86627\OaFlowRestApi\Http\WebService;

class CommonService extends WebService
{
    protected string  $docSubject;                                  //文档标题，不允许为空
    protected string  $fdId;                                        //文档id，不允许为空
    protected string  $fdTemplateId;                                //文档模板id，不允许为空
    protected ?string $docContent;                                  //文档的富文本内容
    protected ?array  $formValues      = [];                        //流程表单数据，允许为空
    protected ?string $docStatus;                                   //文档状态，可以为草稿（"10"）或者待审（"20"）两种状态，默认为待审
    protected ?array  $docCreator      = [];                        //流程发起人，为单值，格式详见人员组织架构的定义说明 ，不允许为空
    protected ?array  $fdKeyword       = [];                        //文档关键字，格式为["关键字1", "关键字2"...]，允许为空
    protected ?array  $docProperty     = [];                        //辅类别，格式为["辅类别1的ID", "辅类别2的ID"...]，允许为空
    protected ?array  $attachmentForms = [];                        //附件列表，允许为空

    /**
     * @param $wsdl
     */
    protected function setWsdl($wsdl)
    {
        $this->wsdl = $wsdl;
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
}