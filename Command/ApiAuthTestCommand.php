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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiAuthTestCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('slack:api:auth-test');
        $this->setDescription('Allows you to test authentication with the Slack API.');
        $this->addArgument(
            'token',
            InputArgument::OPTIONAL,
            'A token to authenticate with, can be left empty to use the currently configured token.'
        );
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
    protected function buildOptions(array $options, InputInterface $input)
    {
        $options['token'] = $input->getArgument('token') ? : $this->getConfiguredToken();

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'auth.test';
    }
}
