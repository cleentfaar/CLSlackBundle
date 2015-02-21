<?php

namespace CL\Bundle\SlackBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterApiClientPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $clientId = 'cl_slack.api_client';
        $mockClientId = 'cl_slack.mock_api_client';

        if (!$container->hasDefinition($clientId) ||
            !$container->hasDefinition($mockClientId) ||
            !$container->hasParameter('cl_slack.test')
        ) {
            return;
        }

        if (!$container->getParameter('cl_slack.test')) {
            return;
        }

        $container->removeDefinition($clientId);
        $container->setAlias($clientId, $mockClientId);
    }
}
