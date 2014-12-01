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

use CL\Slack\Payload\ChannelsInvitePayload;
use CL\Slack\Payload\ChannelsInvitePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsInviteCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:invite');
        $this->setDescription('Invites a user to a channel. The calling user must be a member of the channel.');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to invite the user to');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'The ID of the user to invite');
        $this->setHelp(<<<EOT
The <info>slack:channels:invite</info> command is used to invite a user to a channel.
The calling user must be a member of the channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.invite</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.invite';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsInvitePayload $payload
     * @param InputInterface        $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
        $payload->setUserId($input->getArgument('user-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsInvitePayloadResponse $payloadResponse
     * @param InputInterface                $input
     * @param OutputInterface               $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully invited user to the channel!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $output->writeln('Channel used:');
                $data = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderKeyValueTable($output, $data);
            }
        } else {
            $this->writeError($output, sprintf('Failed to invite user to this channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
