<?php

namespace Philips\Hue\Resources;

class BaseHueResource
{
    public function __construct($data = null)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }
}
