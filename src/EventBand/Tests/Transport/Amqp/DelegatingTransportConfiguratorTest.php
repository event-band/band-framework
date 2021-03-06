<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Tests\Transport\Amqp;

use EventBand\Transport\DelegatingTransportConfigurator;
use EventBand\Transport\TransportConfigurator;
use PHPUnit_Framework_TestCase as TestCase;

class DelegatingTransportConfiguratorTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $internalConfigurator;
    /**
     * @var DelegatingTransportConfigurator
     */
    private $configurator;

    /**
     * Setup configurator
     */
    protected function setUp()
    {
        $this->internalConfigurator = $this->getMock(TransportConfigurator::class);
        $this->internalConfigurator
            ->expects($this->any())
            ->method('supportsDefinition')
            ->will($this->returnCallback(function ($definition) {
                return $definition === 'supported';
            }))
        ;
        $this->configurator = new DelegatingTransportConfigurator([$this->internalConfigurator]);
    }

    /**
     * @test supportsDefinition delegates check to internal configurator
     */
    public function delegatingSupport()
    {
        $this->assertTrue($this->configurator->supportsDefinition('supported'));
        $this->assertFalse($this->configurator->supportsDefinition('notSupported'));
    }

    /**
     * @test setUpDefinition delegates setup to internal definition
     */
    public function delegatingSetup()
    {
        $this->internalConfigurator
            ->expects($this->once())
            ->method('setUpDefinition')
        ;

        $this->configurator->setUpDefinition('supported');
    }

    /**
     * @test setUpDefinition setups all supported configurators
     */
    public function allSupportedConfiguratorsAreUsed()
    {
        $this->internalConfigurator
            ->expects($this->exactly(2))
            ->method('setUpDefinition')
        ;

        $this->configurator->registerConfigurator('test2', $this->internalConfigurator);
        $this->configurator->setUpDefinition('supported');
    }

    /**
     * @test supportsDefinition supports array if all child definitions are supported
     */
    public function arrayDefinitionSupport()
    {
        $this->assertTrue($this->configurator->supportsDefinition(['supported']));
        $this->assertFalse($this->configurator->supportsDefinition(['supported', 'unsupported']));
        $this->assertTrue($this->configurator->supportsDefinition(['supported', 'supported']));
        $this->assertTrue($this->configurator->supportsDefinition([['supported'], 'supported']));
    }

    /**
     * @test setUpDefinition setup array of definitions
     */
    public function delegatingArraySetup()
    {
        $this->internalConfigurator
            ->expects($this->exactly(2))
            ->method('setUpDefinition')
        ;

        $this->configurator->setUpDefinition(['supported', ['supported']]);
    }
}
