<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Tests\Transport\Amqp\Driver;

use EventBand\Transport\Amqp\Driver\CustomAmqpMessage;
use PHPUnit\Framework\TestCase;

class CustomAmqpMessageTest extends TestCase
{
    /**
     * @test get all message properties
     */
    public function messageProperties()
    {
        $message = new AmqpMessageMock();
        $this->assertEquals(
            [
                'body' => $message->getBody(),
                'headers' => $message->getHeaders(),
                'contentType' => $message->getContentType(),
                'contentEncoding' => $message->getContentEncoding(),
                'messageId' => $message->getMessageId(),
                'appId' => $message->getAppId(),
                'userId' => $message->getUserId(),
                'priority' => $message->getPriority(),
                'timestamp' => $message->getTimestamp(),
                'expiration' => $message->getExpiration(),
                'type' => $message->getType(),
                'replyTo' => $message->getReplyTo()
            ],
            CustomAmqpMessage::getMessageProperties($message)
        );
    }

    /**
     * @test get message properties excluding nulls
     */
    public function messageNullProperties()
    {
        $message = new AmqpMessageMock(true);
        $this->assertEquals(
            [
                'body' => $message->getBody(),
                'headers' => $message->getHeaders(),
                'contentType' => $message->getContentType(),
                'contentEncoding' => $message->getContentEncoding(),
                'priority' => $message->getPriority()
            ],
            CustomAmqpMessage::getMessageProperties($message)
        );
    }

    /**
     * @test create message with all properties
     */
    public function messageFromProperties()
    {
        $mock = new AmqpMessageMock();
        $message = CustomAmqpMessage::fromProperties([
            'body' => $mock->getBody(),
            'headers' => $mock->getHeaders(),
            'contentType' => $mock->getContentType(),
            'contentEncoding' => $mock->getContentEncoding(),
            'messageId' => $mock->getMessageId(),
            'appId' => $mock->getAppId(),
            'userId' => $mock->getUserId(),
            'priority' => $mock->getPriority(),
            'timestamp' => $mock->getTimestamp(),
            'expiration' => $mock->getExpiration(),
            'type' => $mock->getType(),
            'replyTo' => $mock->getReplyTo()
        ]);

        $this->assertEquals(
            (new CustomAmqpMessage())
                ->setBody($mock->getBody())
                ->setHeaders($mock->getHeaders())
                ->setContentType($mock->getContentType())
                ->setContentEncoding($mock->getContentEncoding())
                ->setMessageId($mock->getMessageId())
                ->setAppId($mock->getAppId())
                ->setUserId($mock->getUserId())
                ->setPriority($mock->getPriority())
                ->setTimestamp($mock->getTimestamp())
                ->setExpiration($mock->getExpiration())
                ->setType($mock->getType())
                ->setReplyTo($mock->getReplyTo())
            ,
            $message
        );
    }

    /**
     * @test create message with null properties
     */
    public function messageFromPropertiesWithNulls()
    {
        $mock = new AmqpMessageMock(true);
        $message = CustomAmqpMessage::fromProperties([
            'body' => $mock->getBody(),
            'headers' => $mock->getHeaders(),
            'contentType' => $mock->getContentType(),
            'contentEncoding' => $mock->getContentEncoding(),
            'messageId' => $mock->getMessageId(),
            'appId' => $mock->getAppId(),
            'userId' => $mock->getUserId(),
            'priority' => $mock->getPriority(),
        ]);

        $this->assertEquals(
            (new CustomAmqpMessage())
                ->setBody($mock->getBody())
                ->setHeaders($mock->getHeaders())
                ->setContentType($mock->getContentType())
                ->setContentEncoding($mock->getContentEncoding())
                ->setPriority($mock->getPriority())
            ,
            $message
        );
    }

    /**
     * @test copied message has same properties
     */
    public function copyMessageProperties()
    {
        $message = new AmqpMessageMock();
        $this->assertEquals(
            CustomAmqpMessage::getMessageProperties($message),
            CustomAmqpMessage::getMessageProperties(CustomAmqpMessage::createCopy($message))
        );
    }
}
