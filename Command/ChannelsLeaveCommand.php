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

use CL\Slack\Payload\ChannelsLeavePayload;
use CL\Slack\Payload\ChannelsLeavePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsLeaveCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:leave');
        $this->setDescription('Leave a channel (as the user of the token).');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to get information on');
        $this->setHelp(<<<EOT
The <info>slack:channels:leave</info> command leaves a channel as the user of the token.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.leave</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.leave';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsLeavePayload $payload
     * @param InputInterface       $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsLeavePayloadResponse $payloadResponse
     * @param InputInterface               $input
     * @param OutputInterface              $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->isNotInChannel()) {
                $this->writeError($output, 'Could not leave channel; not in channel');
            } else {
                $this->writeOk($output, 'Successfully left channel!');
            }
        } else {
            $this->writeError($output, sprintf('Failed to leave channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
