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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractApiCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $typeAlias = $this->getType();
        $options   = $this->buildOptions([], $input);
        $payload   = $this->createPayload($typeAlias, $options);
        $response  = $this->getTransport()->send($payload);

        return $this->report($this->getTransport()->getRequest()->getUrl(), $payload, $response, $output);
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
     * @return string
     */
    abstract protected function getType();
}
