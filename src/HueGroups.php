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
     * @param string|null $id
     *
     * @return \Philips\Hue\Resources\HueGroupResource|void
     */
    public function get($id = null)
    {
        if ($id === null) {
            return;
        }

        return new HueGroupResource($this->send('/bridge/' . $this->baseUser . '/groups/' . $id));
    }

    /**
     * Set all the lights off in this group
     *
     * @param string $id
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
     * @param string $id
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
     * @param string $id
     * @param array $params
     *
     * @return mixed
     */
    public function customState($id, $params)
    {
        if (!$id) {
            return;
        }

        return $this->send('/bridge/' . $this->baseUser . '/groups/' . $id . '/action', 'put', $params);
    }
}
