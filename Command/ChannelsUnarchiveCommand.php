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

use CL\Slack\Payload\ChannelsUnarchivePayload;
use CL\Slack\Payload\ChannelsUnarchivePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsUnarchiveCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:unarchive');
        $this->setDescription('Unarchives a channel. The token\'s user is automatically added to the channel');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to archive');
        $this->setHelp(<<<EOT
The <info>slack:channels:unarchive</info> command unarchives a given channel.
The user of the token is automatically added to the channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.unarchive</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.unarchive';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsUnarchivePayload $payload
     * @param InputInterface           $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsUnarchivePayloadResponse $payloadResponse
     * @param InputInterface                   $input
     * @param OutputInterface                  $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully un-archived channel!');
        } else {
            $this->writeError($output, sprintf('Failed to un-archive channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
