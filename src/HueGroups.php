<?php

namespace Philips\Hue;

use Philips\Hue\Resources\HueGroupResource;

class HueGroups extends HueClient
{
    public function all()
    {
        return collect($this->send('/bridge/' . $this->baseUser . '/groups'))
            ->map(function ($item) {
                return new HueGroupResource($item);
            });
    }

    public function get($id = null)
    {
        if (!$id) {
            return;
        }

        return new HueGroupResource($this->send('/bridge/' . $this->baseUser . '/groups/' . $id));
    }

    public function off($id)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/groups/' . $id . '/action', 'put', [
            'on' => false
        ]);
    }

    public function on($id)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/groups/' . $id . '/action', 'put', [
            'on' => true
        ]);
    }

    public function customState($id, $params)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/groups/' . $id . '/action', 'put', $params);
    }
}
