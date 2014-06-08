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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractApiCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setHelp(sprintf(<<<EOF
These API commands all follow Slack's API documentation as closely as possible.
You can get detailed usage information about the current command with the URL below:

<info>https://api.slack.com/methods/%s</info>

EOF
        , $this->getType()));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $typeAlias = $this->getType();
        $options   = $this->buildOptions([], $input);
        $payload   = $this->createPayload($typeAlias, $options);
        $response  = $this->getTransport()->send($payload);

        return $this->report($this->getTransport(), $payload, $response, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTransport()
    {
        return $this->getContainer()->get('cl_slack.api.transport');
    }

    /**
     * @param array          $options
     * @param InputInterface $input
     *
     * @return array
     */
    abstract protected function buildOptions(array $options, InputInterface $input);

    /**
     * @todo Find a way so we only have to define the alias in the service definition itself.
     *       This is currently impossible because we need it's value during configure();
     *       where the container is not yet available
     *
     * @return string
     */
    abstract protected function getType();
}
