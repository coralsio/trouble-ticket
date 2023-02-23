<?php

namespace Corals\Modules\TroubleTicket\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class IssueTypePresenter extends FractalPresenter
{
    /**
     * @param array $extras
     * @return IssueTypeTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new IssueTypeTransformer($extras);
    }
}
