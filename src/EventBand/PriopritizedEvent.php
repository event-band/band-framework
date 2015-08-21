<?php

namespace EventBand;

/**
 * Interface PriopritizedEvent
 * @package EventBand
 */
interface PriopritizedEvent extends Event
{

    /**
     * @return mixed
     */
    public function getPriority();

}