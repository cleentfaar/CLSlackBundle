<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class RegisterApiMethodClassesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definitionId      = 'cl_slack.api_method_factory';
        $tag               = 'cl_slack.api_method';
        $requiredAttribute = 'alias';
        $definition        = $container->getDefinition($definitionId);
        $servicesWithTag   = $container->findTaggedServiceIds($tag);

        foreach ($servicesWithTag as $serviceId => $tag) {
            $alias = isset($tag[0][$requiredAttribute])
                ? $tag[0][$requiredAttribute]
                : $serviceId;
            $class = $container->getDefinition($serviceId)->getClass();
            $definition->addMethodCall('addMethodClass', array($class, $alias));
        }
    }
}
