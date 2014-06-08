<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload;

use CL\Bundle\SlackBundle\Slack\Payload\Type\TypeInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class PayloadFactory
{
    /**
     * Registered types
     *
     * @var TypeInterface[]
     */
    protected $types;

    /**
     * Registers a payload type
     *
     * @param TypeInterface $type
     * @param string        $alias
     *
     * @throws \InvalidArgumentException
     */
    public function addType(TypeInterface $type, $alias)
    {
        if (true === $this->hasType($alias)) {
            throw new \InvalidArgumentException(sprintf('Type "%s" already registered', $alias));
        }

        $this->types[$alias] = $type;
    }

    /**
     * Returns if a certain type is registered
     *
     * @param string $alias The type's alias
     *
     * @return bool
     */
    public function hasType($alias)
    {
        return (true === isset($this->types[$alias]));
    }

    /**
     * Returns a registered payload type by alias
     *
     * @param string $alias The type's alias
     *
     * @return TypeInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getType($alias)
    {
        if ($this->hasType($alias) !== true) {
            throw new \InvalidArgumentException(sprintf('Payload type with alias "%s" could not be found', $alias));
        }

        return $this->types[$alias];
    }

    /**
     * @return TypeInterface[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Creates a payload from the given type and options
     *
     * @param string|TypeInterface $type
     * @param array                $options
     *
     * @return Payload
     */
    public function create($type, array $options = array())
    {
        if (false === $type instanceof TypeInterface) {
            $type = $this->getType($type);
        }

        $payload = new Payload($type);
        $payload->setOptions($options);

        return $payload;
    }
}
