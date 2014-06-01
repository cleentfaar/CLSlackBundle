<?php

namespace CL\Bundle\SlackBundle\Slack\Payload;

use CL\Bundle\SlackBundle\Slack\Payload\Type\TypeInterface;

interface PayloadInterface
{
    /**
     * Constructor.
     *
     * @param TypeInterface $type
     */
    public function __construct(TypeInterface $type);

    /**
     * Sets options
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options);

    /**
     * Returns options
     *
     * @return array
     */
    public function getOptions();

    /**
     * Returns the type
     *
     * @return TypeInterface
     */
    public function getType();
}
