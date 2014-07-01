<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class CLSlackExtension extends Extension
{
    /**
     * @param array[]          $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->setParameters($container, $config);
    }

    /**
     * @param ContainerBuilder $container
     * @param string[]         $config
     */
    protected function setParameters(ContainerBuilder $container, array $config)
    {
        if ($config['api_token'] === null) {
            $container->removeDefinition('cl_slack.api_method_transport');
        } else {
            // note the replaceable variable (%s); each ApiMethod can replace it with their own slug
            $container->setParameter('cl_slack.api_token', $config['api_token']);
            $container->setParameter('cl_slack.api_base_url', 'https://slack.com/api/%s');
        }
        $container->setParameter('cl_slack.outgoing_webhooks', $config['outgoing_webhooks']);
    }
}
