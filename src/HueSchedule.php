<?php

namespace Philips\Hue;

use Illuminate\Support\Str;
use Philips\Hue\Resources\HueScheduleResource;

class HueSchedule extends HueClient
{
    public function all()
    {
        return collect($this->send('/bridge/' . $this->baseUser . '/schedules'))
            ->map(function ($item) {
                return new HueScheduleResource($item);
            });
    }

    public function get($id)
    {
        return new HueScheduleResource($this->send('/bridge/' . $this->baseUser . '/schedules/' . $id));
    }
}
