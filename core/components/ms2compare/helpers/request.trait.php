<?php

trait ms2CompareHelperRequest
{
    /**
     * @param string $action
     * @param array $data
     * @return array
     */
    public function handleRequest(string $action, $data = [])
    {
        switch ($action) {
            case 'add':
                $id = $data['record_id'];
                $list = $data['list'];
                $this->resourcesHandler->add($id, $list);
                $output = $this->success($this->modx->lexicon($this::PKG_NAMESPACE . '_scs_add'), [
                    'id' => $id,
                    'list' => $list,
                    'action' => 'add',
                    'totals' => $this->resourcesHandler->getTotals(),
                ]);
                break;
            case 'remove':
                $id = $data['record_id'];
                $list = $data['list'];
                $this->resourcesHandler->remove($id, $list);
                $output = $this->success($this->modx->lexicon($this::PKG_NAMESPACE . '_scs_remove'), [
                    'id' => $id,
                    'list' => $list,
                    'action' => 'remove',
                    'totals' => $this->resourcesHandler->getTotals(),
                ]);
                break;
            case 'clear':
                $list = $data['list'];
                $this->resourcesHandler->clear($list);
                $output = $this->success($this->modx->lexicon($this::PKG_NAMESPACE . '_scs_clear'), [
                    'list' => $list,
                    'action' => 'clear',
                    'totals' => $this->resourcesHandler->getTotals(),
                ]);
                break;
            default:
                $output = $this->error($this->modx->lexicon($this::PKG_NAMESPACE . '_err_action_nf', ['action' => $action]));
                break;
        }
        return $output ?? $this->error($this->modx->lexicon($this::PKG_NAMESPACE . '_err_response_format'));
    }

    /**
     * @param string $message
     * @param array $data
     * @return array
     */
    private function success($message = '', $data = [])
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * @param string $message
     * @param array $data
     * @return array
     */
    private function error($message = '', $data = [])
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
        ];
    }
}
