<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Command;

use CL\Bundle\SlackBundle\Slack\Api\Method\ApiMethodFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractApiCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->addOption(
            'token',
            't',
            InputOption::VALUE_REQUIRED,
            'A token to authenticate with, can be left empty to use the currently configured token.'
        );
        $this->setHelp(sprintf(<<<EOF
These API commands all follow Slack's API documentation as closely as possible.
You can get detailed usage information about the current command with the URL below:

<info>https://api.slack.com/methods/%s</info>

EOF
            , $this->getMethodSlug()));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias    = $this->getMethodAlias();
        $options  = $this->inputToOptions($input);
        $method   = $this->getMethodFactory()->create($alias, $options);
        $request  = $this->getMethodTransport()->getHttpClient()->createRequest('get');
        $response = $this->getMethodTransport()->send($method, $request);

        return $this->report($this->getMethodTransport(), $method, $response, $output);
    }

    /**
     * @return string
     */
    protected function getConfiguredToken()
    {
        return $this->getContainer()->getParameter('cl_slack.api_token');
    }

    /**
     * {@inheritdoc}
     */
    protected function inputToOptions(InputInterface $input)
    {
        $options['token'] = $input->getOption('token') ? : $this->getConfiguredToken();

        return $options;
    }

    /**
     * @return \CL\Bundle\SlackBundle\Slack\Api\Method\Transport\Transport
     */
    protected function getMethodTransport()
    {
        return $this->getContainer()->get('cl_slack.api_method_transport');
    }

    /**
     * @return ApiMethodFactory
     */
    protected function getMethodFactory()
    {
        return $this->getContainer()->get('cl_slack.api_method_factory');
    }

    /**
     * @todo Find a way so we only have to define the alias in the service definition itself.
     *       This is currently impossible because we need it's value during configure();
     *       where the container is not yet available
     *
     * @return string
     */
    protected function getMethodAlias()
    {
        return $this->getMethodSlug();
    }

    /**
     * @return string
     */
    abstract protected function getMethodSlug();
}
