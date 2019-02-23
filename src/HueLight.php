<?php

namespace Philips\Hue;

use Philips\Hue\Resources\HueLightResource;

class HueLight extends HueClient
{
    public function all()
    {
        return collect($this->send('/bridge/' . $this->baseUser . '/lights'))
            ->map(function ($item) {
                return new HueLightResource($item);
            });
    }

    public function get($id = null)
    {
        if (!$id) {
            return;
        }

        return new HueLightResource($this->send('/bridge/' . $this->baseUser . '/lights/' . $id));
    }

    public function off($id)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/lights/' . $id . '/state', 'put', [
            'on' => false
        ]);
    }

    public function on($id)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/lights/' . $id . '/state', 'put', [
            'on' => true
        ]);
    }

    public function breathe($id)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/lights/' . $id . '/state', 'put', [
            'alert' => 'select'
        ]);
    }

    public function customState($id, $params)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/lights/' . $id . '/state', 'put', $params);
    }
}
