<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Serializer;

/**
 * Deserialized object is not match expected type
 */
class UnexpectedResultException extends SerializerException
{
    private $result;
    private $expectedType;

    /**
     * @param mixed           $result       Deserialized result
     * @param string          $expectedType Expected result type
     * @param \Exception|null $previous     Previous exception
     */
    public function __construct($result, $expectedType, \Exception $previous = null)
    {
        $this->result = $result;
        $this->expectedType = $expectedType;

        parent::__construct(sprintf('Expected "%s" object, got "%s"', $expectedType, $this->getResultType()));
    }

    /**
     * Get result
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Get expected type
     *
     * @return string
     */
    public function getExpectedType()
    {
        return $this->expectedType;
    }

    /**
     * Get result type
     *
     * @return string
     */
    public function getResultType()
    {
        return is_object($this->result) ? sprintf('object[%s]', get_class($this->result)) : gettype($this->result);
    }
}
