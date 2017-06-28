<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport\Amqp\Driver;

class MessageConversionException extends \RuntimeException
{
    private $amqpMessage;

    /**
     * @param AmqpMessage     $message
     * @param string          $reason
     * @param \Exception|null $previous
     */
    public function __construct(AmqpMessage $message, $reason, \Exception $previous = null)
    {
        $this->amqpMessage = $message;

        parent::__construct(sprintf('Can not convert message: %s', $reason), 0, $previous);
    }

    public function getAmqpMessage()
    {
        return $this->amqpMessage;
    }
}
