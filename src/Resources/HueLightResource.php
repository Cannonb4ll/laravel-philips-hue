<?php

namespace App\Utilities\Hue\Resources;

class HueLightResource extends BaseHueResource
{
    public function isLightOn()
    {
        return $this->state->on;
    }
}
