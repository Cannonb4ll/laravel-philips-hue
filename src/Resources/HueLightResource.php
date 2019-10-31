<?php

namespace Philips\Hue\Resources;

class HueLightResource extends BaseHueResource
{

    public function isLightOn()
    {
        return $this->state->on;
    }
}
