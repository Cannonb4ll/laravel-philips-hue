<?php

namespace Philips\Hue;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Philips\Hue\Resources\HueUserResource;

class HueUser extends HueClient
{
    /**
     * Creates a new user in your Hue Bridge. When you disable force link button you will have to manually
     * press the link button on your bridge.
     *
     * It will return the username right away.
     *
     * @param null $name
     * @param bool $forceLinkButton
     *
     * @return string
     */
    public function create($name = null, $forceLinkButton = true)
    {
        if (!$name) {
            $name = Str::random(10);
        }

        if ($forceLinkButton) {
            $this->send('/bridge/0/config', 'put', [
                'linkbutton' => true
            ]);
        }

        return object_get(Arr::first($this->send('/bridge', 'post', [
            'devicetype' => env('PHILIPS_HUE_APP_ID') . '#' . $name
        ])), 'success.username');
    }

    /**
     * Returns a new user in your bridge.
     *
     * @param $id
     *
     * @return \Philips\Hue\Resources\HueUserResource
     */
    public function get($id)
    {
        return new HueUserResource($this->send('/bridge/' . $id . '/config'));
    }
}
