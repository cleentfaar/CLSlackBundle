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

use CL\Slack\Api\Method\ChannelsHistoryMethod;
use CL\Slack\Api\Method\Response\ResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiChannelsHistoryCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('slack:api:channels-history');
        $this->setDescription('Returns a portion of messages/events from the specified Slack channel. To read the entire history for a channel, call the method with no latest or oldest arguments, and then continue paging using the instructions in the API documentation');
        $this->addArgument('channel', InputArgument::REQUIRED, 'Channel to fetch history for.');
        $this->addOption('latest', 'l', InputOption::VALUE_REQUIRED, 'Timestamp of the oldest recent seen message.');
        $this->addOption('oldest', 'o', InputOption::VALUE_REQUIRED, 'Timestamp of the latest previously seen message.');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of messages to return, between 1 and 1000.');
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodSlug()
    {
        return ChannelsHistoryMethod::getSlug();
    }

    /**
     * {@inheritdoc}
     */
    protected function inputToOptions(InputInterface $input, array $options)
    {
        $options['channel']    = '#' . ltrim($input->getArgument('channel'), '#');
        $options['latest']  = $input->getOption('latest');
        $options['oldest']  = $input->getOption('oldest');
        $options['count']   = $input->getOption('count');

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function responseToOutput(ResponseInterface $response, OutputInterface $output)
    {
        throw new \Exception('Not yet implemented...');
    }
}
