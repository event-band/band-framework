<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Tests\Transport\Amqp\Driver;

use EventBand\Event;
use EventBand\Serializer\EventSerializer;
use EventBand\Transport\Amqp\Driver\AmqpMessage;
use EventBand\Transport\Amqp\Driver\MessageSerializeConverter;
use EventBand\Transport\Amqp\Driver\CustomAmqpMessage;
use PHPUnit_Framework_TestCase as TestCase;

class MessageSerializeConverterTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;
    /**
     * @var MessageSerializeConverter
     */
    private $converter;

    protected function setUp()
    {
        $this->serializer = $this->getMock(EventSerializer::class);
        $this->converter = new MessageSerializeConverter($this->serializer);
    }

    /**
     * @test event conversion will serialize event to message body
     */
    public function eventSerialization()
    {
        $event = $this->getMock(Event::class);
        $this->serializer
            ->expects($this->once())
            ->method('serializeEvent')
            ->with($event)
            ->will($this->returnValue('serialized string'))
        ;

        $message = $this->converter->eventToMessage($event);
        $this->assertInstanceOf(AmqpMessage::class, $message);
        $this->assertEquals('serialized string', $message->getBody());
    }

    /**
     * @test message conversion will deserialize event from message body
     */
    public function messageBodyDeserialization()
    {
        $message = $this->getMock(AmqpMessage::class);
        $message
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('serialized string'))
        ;

        $event = $this->getMock(Event::class);
        $this->serializer
            ->expects($this->once())
            ->method('deserializeEvent')
            ->with('serialized string')
            ->will($this->returnValue($event))
        ;

        $converted = $this->converter->messageToEvent($message);
        $this->assertSame($event, $converted);
    }

    /**
     * @test eventToMessage get properties from prototype
     */
    public function prototypeProperties()
    {
        $prototype = new AmqpMessageMock();
        $converter = new MessageSerializeConverter($this->serializer, $prototype);
        $event = $this->getMock(Event::class);

        $this->serializer
            ->expects($this->once())
            ->method('serializeEvent')
            ->will($this->returnValue('serialized string'))
        ;

        $message = $converter->eventToMessage($event);
        $this->assertInstanceOf(AmqpMessage::class, $message);

        $messageProperties = CustomAmqpMessage::getMessageProperties($message);
        $expectedProperties = CustomAmqpMessage::getMessageProperties($prototype);
        $expectedProperties['body'] = 'serialized string';

        $this->assertEquals($expectedProperties, $messageProperties);
    }
}
