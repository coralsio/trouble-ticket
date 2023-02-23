<?php

namespace Corals\Modules\TroubleTicket\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class TeamPresenter extends FractalPresenter
{
    /**
     * @param array $extras
     * @return TeamTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new TeamTransformer($extras);
    }
}
