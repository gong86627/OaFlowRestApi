<?php


namespace gong86627\OaFlowRestApi\Business;


use gong86627\OaFlowRestApi\ApiIO\ErrCode;
use Exception;
use gong86627\OaFlowRestApi\ApiIO\IO;

/**
 * 审批流程
 * Class ApproveProcess
 * @package gong86627\OaFlowRestApi\Business
 */
class ApproveProcess extends CommonService
{
    public ?array   $flowParam     = [];                        //流程参数，允许为空
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
                throw new Exception('No result response!', ErrCode::NetErr);
            }
            return IO::success($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
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
                if(!is_array($val) || empty($val)){
                    throw new Exception('Invalid changeNodeHandlers', ErrCode::Business);
                }
                $nodes = [];
                foreach ($val as $key => $users){
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
            "fdId"         => $this->fdId,
            'docCreator'   => json_encode($this->docCreator),
            'docSubject'   => $this->docSubject,
            'fdTemplateId' => $this->fdTemplateId,
            "flowParam"    => json_encode($this->flowParam),
        ];

        if ($this->formValues) {
            $body["formValues"] = json_encode($this->formValues);
        }

        if ($this->attachmentForms) {
            $body['attachmentForms'] = $this->attachmentForms;
        }

        return ['arg0' => $body];
    }
}