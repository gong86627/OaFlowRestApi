<?php
namespace gong86627\OaFlowRestApi\Business;


class AttachmentForm
{
    public string $fdKey        = "";
    public string $fdFileName   = "";
    public $fdAttachment        = null;

    public function __construct($fdKey, $fdFileName, $fdAttachment)
    {
        $this->fdKey = $fdKey;
        $this->fdFileName = $fdFileName;
        $this->fdAttachment = $fdAttachment;
    }
}