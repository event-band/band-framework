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
 * Loader for readers based on a simple array
 */
class EventReaderArrayLoader implements EventConsumerLoader
{
    private $readers;

    /**
     * Constructor with predefined readers
     *
     * @param array $readers An array of [name => reader]
     *
     * @see setReader()
     */
    public function __construct(array $readers = array())
    {
        $this->readers = array();
        foreach ($readers as $name => $reader) {
            $this->setReader($name, $reader);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function loadConsumer($name)
    {
        try {
            return $this->getReader($name);
        } catch (\OutOfBoundsException $e) {
            throw new ConsumerLoadException($name, 'Reader is not found', $e);
        }
    }


    /**
     * Set event reader
     *
     * @param string      $name
     * @param $reader
     *
     * @return $this Provides the fluent interface
     */
    public function setReader($name, $reader)
    {
        $this->readers[$name] = $reader;

        return $this;
    }

    /**
     * Get event reader
     *
     * @param string $name
     *
     * @return callback
     * @throws \OutOfBoundsException If no reader with $name exists
     */
    public function getReader($name)
    {
        if (!$this->hasReader($name)) {
            throw new \OutOfBoundsException(sprintf('Undefined reader "%s"', $name));
        }

        return $this->readers[$name];
    }

    /**
     * Check if reader exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasReader($name)
    {
        return isset($this->readers[$name]);
    }

    /**
     * Remove reader
     *
     * @param string $name
     *
     * @return $this Provides the fluent interface
     * @throws \OutOfBoundsException If no processor with $name exists
     */
    public function removeReader($name)
    {
        if (!$this->hasReader($name)) {
            throw new \OutOfBoundsException(sprintf('Undefined reader "%s"', $name));
        }

        unset($this->readers[$name]);

        return $this;
    }
}
