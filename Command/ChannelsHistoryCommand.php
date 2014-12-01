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

use CL\Slack\Model\SimpleMessage;
use CL\Slack\Payload\ChannelsHistoryPayload;
use CL\Slack\Payload\ChannelsHistoryPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsHistoryCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:history');
        $this->setDescription('Returns a portion of messages/events from the specified channel (see `--help`)');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'Channel to fetch history for');
        $this->addOption('latest', 'l', InputOption::VALUE_REQUIRED, 'Latest message timestamp to include in results');
        $this->addOption('oldest', 'o', InputOption::VALUE_REQUIRED, 'Oldest message timestamp to include in results');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of messages to return, between 1 and 1000.');
        $this->setHelp(<<<EOT
The <info>slack:channels:history</info> command returns a portion of messages/events from the specified channel.
To read the entire history for a channel, run the command with no `latest` or `oldest` options, and then continue paging
using the instructions below.

The messages array up to 100 messages between `--latest` and `--oldest`. If there were more than 100 messages between
those two points, then has_more will be true.

If a message has the same timestamp as latest or oldest it will not be included in the list. This allows a client to fetch
all messages in a hole in channel history, by running the <info>slack:channels:history</info> command with `--latest`
set to the oldest message they have after the hole, and `--oldest` to the latest message they have before the hole.
If the response includes `has_more` then the client can make another call, using the `ts` value of the final messages as
the latest param to get the next page of messages.

If there are more than 100 messages between the two timestamps then the messages returned are the ones closest to latest.
In most cases an application will want the most recent messages and will page backward from there. If oldest is provided
but not latest then the messages returned are those closest to oldest, allowing you to page forward through history if desired.

If either of the latest or oldest arguments are provided then those timestamps will also be included in the output.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.history</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.history';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsHistoryPayload $payload
     * @param InputInterface         $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
        $payload->setLatest($input->getOption('latest'));
        $payload->setOldest($input->getOption('oldest'));
        $payload->setCount($input->getOption('count'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsHistoryPayloadResponse $payloadResponse
     * @param InputInterface                 $input
     * @param OutputInterface                $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully retrieved history');
            $this->renderTable($output, $payloadResponse->getMessages());
            if ($payloadResponse->getLatest() !== null) {
                $output->writeln(sprintf('Latest: <comment>%s</comment>', $payloadResponse->getLatest()));
            }
            if ($payloadResponse->getHasMore() !== null) {
                $output->writeln(sprintf('Has more: <comment>%s</comment>', $payloadResponse->getHasMore() ? 'yes' : 'no'));
            }
        } else {
            $this->writeError($output, sprintf('Failed to retrieve history for this channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
