<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport;

interface TransportConfigurator
{
    public function supportsDefinition($definition);
    public function setUpDefinition($definition, $reset = false);
}
