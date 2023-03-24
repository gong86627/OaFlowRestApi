<?php


namespace gong86627\OaFlowRestApi\Business;

use Exception;
use gong86627\OaFlowRestApi\ApiIO\ErrCode;
use gong86627\OaFlowRestApi\Http\WebService;

class CommonService extends WebService
{
    protected string  $docSubject;                                  //文档标题，不允许为空
    protected string  $fdId            = "";                        //文档id，不允许为空
    protected string  $fdTemplateId;                                //文档模板id，不允许为空
    protected ?string $docContent;                                  //文档的富文本内容
    protected ?array  $formValues      = [];                        //流程表单数据，允许为空
    protected ?string $docStatus;                                   //文档状态，可以为草稿（"10"）或者待审（"20"）两种状态，默认为待审
    protected ?array  $docCreator      = [];                        //流程发起人，为单值，格式详见人员组织架构的定义说明 ，不允许为空
    protected ?array  $fdKeyword       = [];                        //文档关键字，格式为["关键字1", "关键字2"...]，允许为空
    protected ?array  $docProperty     = [];                        //辅类别，格式为["辅类别1的ID", "辅类别2的ID"...]，允许为空
    protected ?array  $attachmentForms = [];                        //附件列表，允许为空
    protected ?array  $flowParam       = [];                        //流程参数，允许为空

    protected array $operationType = [
        'handler_pass'              => '通过',
        'handler_refuse'            => '驳回',
        'handler_abandon'           => '废弃',
        'handler_commission'        => '转办',
        'handler_communicate'       => '沟通',
        'handler_returnCommunicate' => '回复沟通',
        'handler_cancelCommunicate' => '取消沟通',
        'handler_additionSign'      => '补签',
        'handler_nodeSuspend'       => '节点暂停',
        'handler_assign'            => '加签',
        'handler_assignCancel'      => '收回加签',
        'handler_assignRefuse'      => '退回加签',
        'handler_superRefuse'       => '超级驳回'
    ];

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

    /**
     * @param $name
     * @param $val
     * {operationType:"handler_pass",auditNote:"审批意见", futureNodeId:"节点名", changeNodeHandlers:["节点名1：用户ID1; 用户ID2...", "节点名2：用户ID1; 用户ID2..."...], operParam:{...}}，不允许为空
     * @throws Exception
     */
    public function setFlowParam($name, $val)
    {
        switch ($name) {
            case 'operationType':
                if (array_search($val, $this->operationType) === false) {
                    throw new Exception('operationType is not supported', ErrCode::Business);
                }
                $operatorArr = array_flip($this->operationType);
                $val = $operatorArr[$val];
                break;
            case 'changeNodeHandlers':
                if (!is_array($val) || empty($val)) {
                    throw new Exception('Invalid changeNodeHandlers', ErrCode::Business);
                }
                $nodes = [];
                foreach ($val as $key => $users) {
                    $nodes[] = $key.':'.implode(';', $users);
                }
                $val = $nodes;
                break;
            case 'auditNote':    //审批意见，不允许为空
                break;
        }

        $this->flowParam[$name] = $val;
    }

    /**
     * set params for request
     * @return array
     * @throws Exception
     */
    protected function processSendParams(): array
    {
        if (empty($this->fdId) || empty($this->fdTemplateId) || empty($this->docCreator) || empty($this->docSubject) || empty($this->flowParam)) {
            throw new Exception('You must specify', ErrCode::ParamEmpty);
        }
        if (empty($this->flowParam['operationType']) || empty($this->flowParam['auditNote'])) {
            throw new Exception('flow parameter is not set!', ErrCode::ParamEmpty);
        }

        $body = [
            'docCreator'   => json_encode($this->docCreator),
            'docSubject'   => $this->docSubject,
            'fdTemplateId' => $this->fdTemplateId
        ];

        if($this->fdId){
            $body["fdId"] = $this->fdId;
        }

        if($this->flowParam){
            $body["flowParam"] = json_encode($this->flowParam);
        }

        if ($this->formValues) {
            $body["formValues"] = json_encode($this->formValues);
        }

        if ($this->attachmentForms) {
            $body['attachmentForms'] = $this->attachmentForms;
        }

        return ['arg0' => $body];
    }
}