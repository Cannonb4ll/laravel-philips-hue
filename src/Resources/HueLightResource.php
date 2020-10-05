<?php

namespace Philips\Hue\Resources;

class HueLightResource extends BaseHueResource
{
    public $state;

    public function isLightOn()
    {
        return $this->state->on;
    }
}
