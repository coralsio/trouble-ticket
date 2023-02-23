<?php

namespace Corals\Modules\TroubleTicket\Services;

use Corals\Foundation\Services\BaseServiceClass;

class IssueTypeService extends BaseServiceClass
{
    protected $excludedRequestParams = ['categories'];

    /**
     * @param $request
     * @param $additionalData
     */
    public function postStoreUpdate($request, &$additionalData)
    {
        $this->model->categories()->sync($request->get('categories'));
    }
}
