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
use CL\Slack\Payload\GroupsHistoryPayload;
use CL\Slack\Payload\GroupsHistoryPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsHistoryCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:history');
        $this->setDescription('Returns a portion of messages/events from the specified private group (see `--help`)');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'ID of the group to fetch history for');
        $this->addOption('latest', 'l', InputOption::VALUE_REQUIRED, 'Latest message timestamp to include in results');
        $this->addOption('oldest', 'o', InputOption::VALUE_REQUIRED, 'Oldest message timestamp to include in results');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of messages to return, between 1 and 1000.');
        $this->setHelp(<<<EOT
The <info>slack:groups:history</info> command returns a portion of messages/events from the specified private group.
To read the entire history for a group, call the method with no latest or oldest arguments, and then continue paging
using the instructions below.

The messages array up to 100 messages between `--latest` and `--oldest`. If there were more than 100 messages between
those two points, then `has_more` will be true.

If a message has the same timestamp as latest or oldest it will not be included in the list. This allows a client to
fetch all messages in a hole in channel history, by running <info>slack:groups:history</info> with `--latest` set to the
oldest message they have after the hole, and `--oldest` to the latest message they have before the hole.
If the response includes `has_more` then the client can make another call, using the `ts` value of the final messages as
the latest param to get the next page of messages.

If there are more than 100 messages between the two timestamps then the messages returned are the ones closest to latest.
In most cases an application will want the most recent messages and will page backward from there. If oldest is provided
but not latest then the messages returned are those closest to oldest, allowing you to page forward through history if
desired.

If the latest or oldest arguments are provided then those timestamps will also be included in the output.

Messages of type "message" are user-entered text messages sent to the group, while other types are events that happened
within the group. All messages have both a type and a sortable ts, but the other fields depend on the type. For a list
of all possible events, see the channel messages documentation: <comment>https://api.slack.com/docs/messages</comment>

If a message has been starred by the calling user, the `is_starred` property will be present and true. This property is
only added for starred items, so is not present in the majority of messages.

The `is_limited` boolean property is only included for free teams that have reached the free message limit. If true,
there are messages before the current result set, but they are beyond the message limit.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.history</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'groups.history';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsHistoryPayload $payload
     * @param InputInterface         $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setGroupId($input->getArgument('group-id'));
        $payload->setLatest($input->getOption('latest'));
        $payload->setOldest($input->getOption('oldest'));
        $payload->setCount($input->getOption('count'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsHistoryPayloadResponse $payloadResponse
     * @param InputInterface                 $input
     * @param OutputInterface                $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $messages = array_map(function (SimpleMessage $message) {
                return $this->serializeObjectToArray($message);
            }, $payloadResponse->getMessages());

            $firstMessage = reset($messages);
            $headers      = array_keys($firstMessage);

            $this->writeOk($output, 'Successfully retrieved history');
            $this->renderTable($output, $messages, $headers);
            if ($payloadResponse->getLatest() !== null) {
                $output->writeln(sprintf('Latest: <comment>%s</comment>', $payloadResponse->getLatest()));
            }
            if ($payloadResponse->getHasMore() !== null) {
                $output->writeln(sprintf('Has more: <comment>%s</comment>', $payloadResponse->getHasMore() ? 'yes' : 'no'));
            }
        } else {
            $this->writeError($output, sprintf('Failed to retrieve history: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
