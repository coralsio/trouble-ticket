<?php

namespace Corals\Modules\TroubleTicket\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class TroubleTicketPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return TroubleTicketTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new TroubleTicketTransformer($extras);
    }
}
