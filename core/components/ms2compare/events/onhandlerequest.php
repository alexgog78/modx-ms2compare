<?php

/**
 * Handle ajax requests
 */
class ms2CompareEventOnHandleRequest extends abstractModuleEvent
{
    /** @var bool */
    public static $useMgrContext = false;

    /** @var string */
    private $actionKey;

    /** @var string */
    private $actionValue;

    /**
     * ms2CompareEventOnHandleRequest constructor.
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
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        if (empty($this->actionValue) || !$isAjax) {
            return;
        }
        $response = $this->service->handleRequest($this->actionValue, $_REQUEST);
        @session_write_close();
        exit($this->modx->toJSON($response));
    }
}
