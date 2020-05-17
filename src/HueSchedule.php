<?php

namespace Philips\Hue;

use Philips\Hue\Resources\HueScheduleResource;

class HueSchedule extends HueClient
{
    /**
     * Returns all the schedules in your bridge
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return collect($this->send('/bridge/' . $this->baseUser . '/schedules'))
            ->map(function ($item) {
                return new HueScheduleResource($item);
            });
    }

    /**
     * Returns a specific schedule object
     *
     * @param string $id
     *
     * @return \Philips\Hue\Resources\HueScheduleResource
     */
    public function get($id)
    {
        return new HueScheduleResource($this->send('/bridge/' . $this->baseUser . '/schedules/' . $id));
    }
}
