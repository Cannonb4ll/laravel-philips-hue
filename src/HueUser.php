<?php

namespace Philips\Hue;

use Illuminate\Support\Str;
use Philips\Hue\Resources\HueUserResource;

class HueUser extends HueClient
{
    public function create($name = null)
    {
        if (!$name) {
            $name = Str::random();
        }

        return $this->send('/bridge', 'post', [
            'devicetype' => env('PHILIPS_HUE_APP_ID') . '#' . $name
        ]);
    }

    public function get($id)
    {
        return new HueUserResource($this->send('/bridge/' . $id . '/config'));
    }
}
