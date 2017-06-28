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
 * Definition is not supported by configurator
 */
class UnsupportedDefinitionException extends ConfiguratorException
{
    private $definition;

    /**
     * @param mixed           $definition
     * @param string          $reason
     * @param \Exception|null $previous
     */
    public function __construct($definition, $reason, \Exception $previous = null)
    {
        parent::__construct(sprintf('Unsupported definition: %s', $reason), 0, $previous);
    }

    public function getDefinition()
    {
        return $this->definition;
    }
}