<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Api\Method;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiMethodFactory
{
    /**
     * Registered method classes
     *
     * @var array<string>
     */
    protected $methodClasses;

    /**
     * Registers an API method using a given alias
     *
     * @param string $methodClass
     * @param string $alias
     *
     * @throws \InvalidArgumentException
     */
    public function addMethodClass($methodClass, $alias)
    {
        if (true === $this->hasMethodClass($methodClass)) {
            throw new \InvalidArgumentException(sprintf('Method "%s" already registered', $alias));
        }

        $this->methodClasses[$alias] = $methodClass;
    }

    /**
     * Returns if a certain method is registered
     *
     * @param string $alias The method's alias
     *
     * @return bool
     */
    public function hasMethodClass($alias)
    {
        return (true === isset($this->methodClasses[$alias]));
    }

    /**
     * Returns a registered method's class by alias
     *
     * @param string $alias The method's alias
     *
     * @return string The method's FQCN
     *
     * @throws \InvalidArgumentException
     */
    public function getMethodClass($alias)
    {
        if ($this->hasMethodClass($alias) !== true) {
            throw new \InvalidArgumentException(sprintf('There is no method registered with that alias: "%s"', $alias));
        }

        return $this->methodClasses[$alias];
    }

    /**
     * @return array
     */
    public function getMethodClasses()
    {
        return $this->methodClasses;
    }

    /**
     * Creates a payload from the given type and options
     *
     * @param string $alias
     * @param array  $options
     *
     * @return ApiMethodInterface
     */
    public function create($alias, array $options = array())
    {
        $methodClass  = $this->getMethodClass($alias);
        $method = new $methodClass($options);

        return $method;
    }
}
