<?php

/**
 * Handle non-ajax requests
 */
class ms2CompareEventOnLoadWebDocument extends abstractModuleEvent
{
    /** @var bool */
    public static $useMgrContext = false;

    /** @var string */
    private $actionKey;

    /** @var string */
    private $actionValue;

    /**
     * ms2CompareEventOnLoadWebDocument constructor.
     *
     * @param abstractModule $service
     * @param array $scriptProperties
     */
    public function __construct(abstractModule $service, $scriptProperties = [])
    {
        parent::__construct($service, $scriptProperties);
        $this->actionKey = $this->service::PKG_NAMESPACE . '_action';
        $this->actionValue = $_REQUEST[$this->actionKey];
    }

    public function run()
    {
        if (empty($this->actionValue)) {
            return;
        }
        $response = $this->service->handleRequest($this->actionValue, $_REQUEST);
        $this->modx->setPlaceholder('request_status', $response['success']);
        $this->modx->setPlaceholder('request_message', $response['message']);
    }
}
