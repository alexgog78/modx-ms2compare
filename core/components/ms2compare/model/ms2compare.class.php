<?php

$this->loadClass('abstractModule', MODX_CORE_PATH . 'components/abstractmodule/model/', true, true);

require_once dirname(__DIR__) . '/helpers/request.trait.php';

class ms2Compare extends abstractModule
{
    use ms2CompareHelperRequest;

    const PKG_VERSION = '1.0.0';
    const PKG_RELEASE = 'beta';
    const PKG_NAMESPACE = 'ms2compare';
    const DEVELOPER_MODE = true;//TODO

    /** @var array */
    public $session = [];

    /** @var ms2CompareResourcesHandler */
    public $resourcesHandler;

    /** @var bool */
    protected $loadPackage = false;

    /**
     * ms2Compare constructor.
     *
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX $modx, array $config = [])
    {
        parent::__construct($modx, $config);
        $contextKey = $this->modx->context->key;
        $this->initSession($contextKey);
        $this->initHandler($contextKey);
    }

    /**
     * @param string $contextKey
     */
    private function initSession(string $contextKey)
    {
        if ($contextKey == 'mgr') {
            return;
        }
        $this->session = &$_SESSION[$this::PKG_NAMESPACE];
        if (empty($this->session) || !is_array($this->session)) {
            $this->session = [];
        }
    }

    /**
     * @param string $contextKey
     */
    private function initHandler(string $contextKey)
    {
        if ($contextKey == 'mgr') {
            return;
        }
        $handlerClass = $this->modx->loadClass('ms2CompareResourcesHandler', __DIR__ . '/', true, true);
        $this->resourcesHandler = new $handlerClass($this);
    }
}
