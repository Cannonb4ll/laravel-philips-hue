<?php

namespace Philips\Hue;

use Philips\Hue\Resources\HueGroupResource;

class HueGroups extends HueClient
{
    /**
     * Returns all the groups in this bridge
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return collect($this->send('/bridge/' . $this->baseUser . '/groups'))
            ->map(function ($item) {
                return new HueGroupResource($item);
            });
    }

    /**
     * Return a specific group object
     *
     * @param null $id
     *
     * @return \Philips\Hue\Resources\HueGroupResource|void
     */
    public function get($id = null)
    {
        if (!$id) {
            return;
        }

        return new HueGroupResource($this->send('/bridge/' . $this->baseUser . '/groups/' . $id));
    }

    /**
     * Set all the lights off in this group
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

        return $this->send('/bridge/' . $this->baseUser . '/groups/' . $id . '/action', 'put', [
            'on' => false
        ]);
    }

    /**
     * Set all the lights on in this group
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

        return $this->send('/bridge/' . $this->baseUser . '/groups/' . $id . '/action', 'put', [
            'on' => true
        ]);
    }

    /**
     * Send in your custom states, you can find the parameters here:
     * https://developers.meethue.com/develop/hue-api/groupds-api/#set-gr-state
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

        return $this->send('/bridge/' . $this->baseUser . '/groups/' . $id . '/action', 'put', $params);
    }
}
