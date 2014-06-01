<?php

namespace CL\Bundle\SlackBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RegisterPayloadTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definitionId      = 'cl_slack.payload_factory';
        $tag               = 'cl_slack.payload_type';
        $requiredAttribute = 'alias';
        $definition        = $container->getDefinition($definitionId);
        $servicesWithTag   = $container->findTaggedServiceIds($tag);

        foreach ($servicesWithTag as $serviceId => $tag) {
            $alias = isset($tag[0][$requiredAttribute])
                ? $tag[0][$requiredAttribute]
                : $serviceId;

            $definition->addMethodCall('addType', array(new Reference($serviceId), $alias));
        }
    }
}
