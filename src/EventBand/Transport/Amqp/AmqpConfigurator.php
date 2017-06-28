<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport\Amqp;

use Che\LogStock\LoggerFactory;
use EventBand\Transport\Amqp\Definition\AmqpDefinition;
use EventBand\Transport\Amqp\Driver\AmqpDriver;
use EventBand\Transport\Amqp\Driver\DriverException;
use EventBand\Transport\ConfiguratorException;
use EventBand\Transport\TransportConfigurator;
use EventBand\Transport\UnsupportedDefinitionException;

/**
 * Configurator for AMQP definitions
 */
class AmqpConfigurator implements TransportConfigurator
{
    private $driver;
    private $logger;

    public function __construct(AmqpDriver $driver)
    {
        $this->driver = $driver;
        $this->logger = LoggerFactory::getLogger(__CLASS__);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDefinition($definition)
    {
        return $definition instanceof AmqpDefinition;
    }

    /**
     * {@inheritDoc}
     */
    public function setUpDefinition($definition, $reset = false)
    {
        if (!$this->supportsDefinition($definition)) {
            throw new UnsupportedDefinitionException($definition, 'Definition is not an AmqpDefinition');
        }

        /** @var $definition AmqpDefinition */
        try {
            foreach ($definition->getExchanges() as $exchange) {
                if ($reset) {
                    $this->logger->debug('Delete exchange', ['exchange' => $exchange]);
                    $this->driver->deleteExchange($exchange);
                }
                $this->logger->debug('Declare exchange', ['exchange' => $exchange]);
                $this->driver->declareExchange($exchange);
            }
            foreach ($definition->getExchanges() as $exchange) {
                foreach ($exchange->getBindings() as $source => $routingKeys) {
                    foreach ($routingKeys as $routingKey) {
                        $this->logger->debug('Bind exchange', [
                            'target' => $exchange->getName(),
                            'source' => $source,
                            'routingKey' => $routingKey
                        ]);
                        $this->driver->bindExchange($exchange->getName(), $source, $routingKey);
                    }
                }
            }

            foreach ($definition->getQueues() as $queue) {
                if ($reset) {
                    $this->logger->debug('Delete queue', ['queue' => $queue]);
                    $this->driver->deleteQueue($queue);
                }
                $this->logger->debug('Declare queue', ['queue' => $queue]);
                $this->driver->declareQueue($queue);
                foreach ($queue->getBindings() as $exchange => $routingKeys) {
                    foreach ($routingKeys as $routingKey) {
                        $this->logger->debug('Bind queue', [
                            'queue' => $queue->getName(),
                            'exchange' => $exchange,
                            'routingKey' => $routingKey
                        ]);
                        $this->driver->bindQueue($queue->getName(), $exchange, $routingKey);
                    }
                }
            }
        } catch (DriverException $e) {
            throw new ConfiguratorException('Driver error on declare', 0, $e);
        }
    }
}
