<?php

namespace Philips\Hue;

use Philips\Hue\Resources\HueLightResource;

class HueLight extends HueClient
{
    /**
     * Returns all the lights in your bridge
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return collect($this->send('/bridge/' . $this->baseUser . '/lights'))
            ->map(function ($item) {
                return new HueLightResource($item);
            });
    }

    /**
     * Returns a specific light object
     *
     * @param null $id
     *
     * @return \Philips\Hue\Resources\HueLightResource|void
     */
    public function get($id = null)
    {
        if (!$id) {
            return;
        }

        return new HueLightResource($this->send('/bridge/' . $this->baseUser . '/lights/' . $id));
    }

    /**
     * Change the light state to off
     *
     * @param $id
     *
     * @return mixed|void
     */
    public function off($id)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/lights/' . $id . '/state', 'put', [
            'on' => false
        ]);
    }

    /**
     * Change the light state to on
     *
     * @param $id
     *
     * @return mixed|void
     */
    public function on($id)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/lights/' . $id . '/state', 'put', [
            'on' => true
        ]);
    }

    /**
     * Make the light do a specific 'breathe' animation
     *
     * @param $id
     *
     * @return mixed|void
     */
    public function breathe($id)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/lights/' . $id . '/state', 'put', [
            'alert' => 'select'
        ]);
    }

    /**
     * Send in your custom states, you can find the parameters here:
     * https://developers.meethue.com/develop/hue-api/lights-api/#set-light-state
     *
     * @param $id
     * @param $params
     *
     * @return mixed|void
     */
    public function customState($id, $params)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/lights/' . $id . '/state', 'put', $params);
    }
}
