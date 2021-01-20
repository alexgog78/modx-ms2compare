<?php

class ms2CompareResourcesHandler
{
    /** @var ms2Compare */
    private $service;

    /** @var modX */
    private $modx;

    /** @var string */
    private $context;

    /** @var array */
    private $resources = [];

    /**
     * ms2CompareHandler constructor.
     *
     * @param ms2Compare $service
     */
    public function __construct(ms2Compare $service)
    {
        $this->service = $service;
        $this->modx = $service->modx;
        $this->modx->lexicon->load($service::PKG_NAMESPACE . ':status', $service::PKG_NAMESPACE . ':list');
        $checkContext = $this->modx->getOption($service::PKG_NAMESPACE . '_check_context', [], true, false);
        $this->context = $checkContext ? $this->modx->context->get('key') : 'web';
        $this->resources = &$service->session[$this->context];
        if (empty($this->resources) || !is_array($this->resources)) {
            $this->resources = [];
        }
    }

    /**
     * @param string $list
     */
    public function initializeList($list = 'default')
    {
        if (!$this->resources[$list]) {
            $this->resources[$list] = [];
        }
    }

    /**
     * @return array
     */
    public function getListKeys()
    {
        return array_keys($this->resources);
    }

    /**
     * @return array
     */
    public function getTotals()
    {
        $output = [
            'total_count' => 0,
            'lists' => [],
        ];
        $lists = $this->getListKeys();
        foreach ($lists as $list) {
            $total = $this->getListTotal($list);
            $output['total_count'] += $total;
            $output['lists'][$list] = $total;
        }
        return $output;
    }

    /**
     * @param string $list
     * @return int
     */
    public function getListTotal($list = '')
    {
        if (!$this->resources[$list]) {
            $this->clear($list);
        }
        return count($this->resources[$list]);
    }

    /**
     * @param int $id
     * @param string $list
     * @return bool
     */
    public function check(int $id, $list = 'default')
    {
        $this->initializeList($list);
        return isset($this->resources[$list][$id]);
    }

    /**
     * @param string $list
     * @return mixed
     */
    public function get($list = 'default')
    {
        $this->initializeList($list);
        return $this->resources[$list];
    }

    /**
     * @param int $id
     * @param string $list
     */
    public function add(int $id, $list = 'default')
    {
        $this->initializeList($list);
        $this->resources[$list][$id] = $id;
    }

    /**
     * @param int $id
     * @param string $list
     */
    public function remove(int $id, $list = 'default')
    {
        $this->initializeList($list);
        unset($this->resources[$list][$id]);
    }

    /**
     * @param string $list
     */
    public function clear($list = 'default')
    {
        $this->resources[$list] = [];
    }
}
