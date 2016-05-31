<?php

namespace Linfo\Laravel\Models;

use Linfo\Laravel\Traits\LinfoProcessedTrait;

/**
 * Class Linfo
 * @package Linfo\Laravel\Models
 */
class Linfo extends Model
{
    use LinfoProcessedTrait;

    /**
     * @var array
     */
    protected $dates = [
        'timestamp',
    ];

    /**
     * Linfo constructor.
     *
     * @throws \LinfoFatalException
     */
    public function __construct()
    {
        $linfo = new \Linfo(config('linfo.source'));
        $linfo->scan();
        $this->setup($linfo->getInfo());
    }

    /**
     * @param array $attributes
     */
    protected function setup(array $attributes)
    {
        $this->setOriginals($attributes);
        $this->setAttributes($this->originals);
        $this->setProcesseds();
    }

    /**
     * @param array|string $value
     */
    public function setUptimeAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['uptime'] = $value;
            $this->attributes['uptime']['bootedtimestamp'] = $this->asDateTime($value['bootedtimestamp']);
        } else {
            $valArray = explode(';', $value);
            $uptime = trim($valArray[0]);
            $bootedtime = trim(explode(' ', trim($valArray[1]))[1]);
            $this->attributes['uptime']['text'] = $uptime;
            $this->attributes['uptime']['bootedtimestamp'] = $this->asDateTime($bootedtime);
        }
    }
}
