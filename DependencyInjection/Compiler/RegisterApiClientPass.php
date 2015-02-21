<?php

namespace CL\Bundle\SlackBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterApiClientPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $clientId = 'cl_slack.api_client';

        if (!$container->hasDefinition($clientId) ||
            !$container->hasParameter('cl_slack.test') ||
            !$container->hasParameter('cl_slack.mock_api_client.class')
        ) {
            return;
        }

        if (!$container->getParameter('cl_slack.test')) {
            return;
        }

        $clientDefinition = $container->getDefinition($clientId);
        $clientDefinition->setClass($container->getParameter('cl_slack.mock_api_client.class'));
    }
}
