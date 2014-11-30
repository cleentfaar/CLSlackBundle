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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterPayloadsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('cl_slack.payload_registry');

        foreach ($container->findTaggedServiceIds('cl_slack.payload') as $id => $tags) {
            $registryDefinition->addMethodCall('register', [new Reference($id)]);
        }
    }
}
