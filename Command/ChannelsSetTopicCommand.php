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

use CL\Slack\Payload\ChannelsSetTopicPayload;
use CL\Slack\Payload\ChannelsSetTopicPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsSetTopicCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:set-topic');
        $this->setDescription('Change the topic of a channel. The calling user must be a member of the channel.');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to change the topic of');
        $this->addArgument('topic', InputArgument::REQUIRED, 'The new topic');
        $this->setHelp(<<<EOT
The <info>slack:channels:set-topic</info> command changes the topic of a channel.
The calling user must be a member of the channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.set-topic</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.setTopic';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsSetTopicPayload $payload
     * @param InputInterface            $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
        $payload->setTopic($input->getArgument('topic'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsSetTopicPayloadResponse $payloadResponse
     * @param InputInterface                    $input
     * @param OutputInterface                   $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, sprintf('Successfully changed topic of channel to: "%s"', $payloadResponse->getTopic()));
        } else {
            $this->writeError($output, sprintf('Failed to change topic of channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
