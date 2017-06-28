<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport;

/**
 * Load reader by name
 */
interface EventConsumerLoader
{
    /**
     * Load event reader
     *
     * @param string $name
     *
     * @return EventConsumer
     * @throws ConsumerLoadException
     */
    public function loadConsumer($name);
}
