<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
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