<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Tests\Transport\Amqp;

use EventBand\Event;
use EventBand\Routing\EventRouter;
use EventBand\Transport\Amqp\AmqpPublisher;
use EventBand\Transport\Amqp\Driver\MessageEventConverter;
use EventBand\Transport\Amqp\Driver\AmqpDriver;
use EventBand\Transport\Amqp\Driver\AmqpMessage;
use EventBand\Transport\Amqp\Driver\DriverException;
use EventBand\Transport\Amqp\Driver\MessagePublication;
use EventBand\Transport\Amqp\Driver\EventConversionException;
use EventBand\Transport\PublishEventException;
use PHPUnit_Framework_TestCase as TestCase;

class AmqpPublisherTest extends TestCase
{
    /**
     * @var AmqpPublisher
     */
    private $publisher;
    /**
     * @var AmqpDriver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $driver;
    /**
     * @var MessageEventConverter|\PHPUnit_Framework_MockObject_MockObject
     */
    private $converter;
    /**
     * @var EventRouter|\PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * Set up consumer
     */
    protected function setUp()
    {
        $this->driver = $this->getMock(AmqpDriver::class);
        $this->converter = $this->getMock(MessageEventConverter::class);
        $this->router = $this->getMock(EventRouter::class);
        $this->publisher = new AmqpPublisher($this->driver, $this->converter, 'exchange');
    }

    /**
     * @test publish uses driver for publishing
     */
    public function delegateDriverPublish()
    {
        $event = $this->createEvent();
        $message = $this->createMessage();

        $this->converter
            ->expects($this->any())
            ->method('eventToMessage')
            ->will($this->returnValue($message))
        ;

        $this->driver
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(MessagePublication::class), 'exchange', '')
        ;

        $this->publisher->publishEvent($event);
    }

    /**
     * @test message publication is created from event and internal parameters
     */
    public function messagePublicationFactory()
    {
        $event = $this->createEvent();
        $message = $this->createMessage();

        $this->converter
            ->expects($this->once())
            ->method('eventToMessage')
            ->with($event)
            ->will($this->returnValue($message))
        ;

        $this->driver
            ->expects($this->once())
            ->method('publish')
            ->with(
                $this->callback(function (MessagePublication $publication) use ($message) {
                    $this->assertSame($message, $publication->getMessage());
                    $this->assertFalse($publication->isPersistent());
                    $this->assertTrue($publication->isMandatory());
                    $this->assertFalse($publication->isImmediate());

                    return true;
                }),
                $this->anything(),
                $this->anything()
            )
        ;

        $publisher = new AmqpPublisher(
            $this->driver, $this->converter, 'exchange', null,
            false, true, false
        );

        $publisher->publishEvent($event);
    }

    /**
     * @test routingKey is generated by event router
     */
    public function eventRoutingKey()
    {
        $event = $this->createEvent();
        $message = $this->createMessage();

        $this->converter
            ->expects($this->any())
            ->method('eventToMessage')
            ->will($this->returnValue($message))
        ;

        $router = $this->getMock(EventRouter::class);
        $router
            ->expects($this->once())
            ->method('routeEvent')
            ->with($event)
            ->will($this->returnValue('eventRoutingKey'))
        ;

        $this->driver
            ->expects($this->once())
            ->method('publish')
            ->with($this->anything(), $this->anything(), 'eventRoutingKey')
        ;

        $publisher = new AmqpPublisher(
            $this->driver, $this->converter, 'exchange', $router,
            false, true, false
        );

        $publisher->publishEvent($event);
    }

    /**
     * @test publication exception on message conversion error
     */
    public function conversionException()
    {
        $event = $this->createEvent();
        $conversionException = new EventConversionException($event, 'Conversion error');

        $this->converter
            ->expects($this->any())
            ->method('eventToMessage')
            ->will($this->throwException($conversionException))
        ;

        try {
            $this->publisher->publishEvent($event);
        } catch (PublishEventException $e) {
            $this->assertSame($conversionException, $e->getPrevious());

            return;
        }

        $this->fail('Exception was not thrown');
    }

    /**
     * @test publication exception on driver error
     */
    public function driverException()
    {
        $event = $this->createEvent();
        $message = $this->createMessage();
        $driverException = new DriverException('Error');

        $this->converter
            ->expects($this->any())
            ->method('eventToMessage')
            ->will($this->returnValue($message))
        ;

        $this->driver
            ->expects($this->once())
            ->method('publish')
            ->will($this->throwException($driverException))
        ;

        try {
            $this->publisher->publishEvent($event);
        } catch (PublishEventException $e) {
            $this->assertSame($driverException, $e->getPrevious());

            return;
        }

        $this->fail('Exception was not thrown');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Event
     */
    private function createEvent()
    {
        return $this->getMock('EventBand\Event');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AmqpMessage
     */
    private function createMessage()
    {
        return $this->getMock(AmqpMessage::class);
    }
}
