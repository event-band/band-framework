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
 * Delegates configuration for internal configurators
 */
class DelegatingTransportConfigurator implements TransportConfigurator
{
    /**
     * @var TransportConfigurator[]
     */
    private $configurators;

    public function __construct(array $configurators = [])
    {
        foreach ($configurators as $name => $configurator) {
            $this->registerConfigurator($name, $configurator);
        }
    }

    public function registerConfigurator($name, TransportConfigurator $configurator)
    {
        $this->configurators[$name] = $configurator;
    }

    public function getConfigurators()
    {
        return $this->configurators;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDefinition($definition)
    {
        if (is_array($definition)) {
            foreach ($definition as $child) {
                if (!$this->supportsDefinition($child)) {
                    return false;
                }
            }

            return true;
        }

        foreach ($this->configurators as $configurator) {
            if ($configurator->supportsDefinition($definition)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function setUpDefinition($definition, $reset = false)
    {
        if (is_array($definition)) {
            foreach ($definition as $child) {
                $this->setUpDefinition($child, $reset);
            }

            return;
        }

        $setup = false;
        foreach ($this->configurators as $configurator) {
            if ($configurator->supportsDefinition($definition)) {
                $configurator->setUpDefinition($definition, $reset);
                $setup = true;
            }
        }

        if (!$setup) {
            throw new UnsupportedDefinitionException($definition, sprintf('No configurator found for definition'));
        }
    }
}