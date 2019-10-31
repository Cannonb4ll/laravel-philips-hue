<?php

namespace Philips\Hue\Resources;

class HueLightResource extends BaseHueResource
{
    /**
     * @return mixed
     */
    public function isLightOn()
    {
        return $this->state->on;
    }
}
