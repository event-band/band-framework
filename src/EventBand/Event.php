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
 * Event
 */
interface Event
{
    /**
     * Get event name
     *
     * @return string
     */
    public function getName();
}
