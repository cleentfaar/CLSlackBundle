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

use CL\Slack\Payload\ChannelsArchivePayload;
use CL\Slack\Payload\ChannelsArchivePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsArchiveCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:archive');
        $this->setDescription('Archives a given Slack channel');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to archive');
        $this->setHelp(<<<EOT
The <info>slack:channels:archive</info> command let's you archive a given Slack channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.archive</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.archive';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsArchivePayload $payload
     * @param InputInterface         $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsArchivePayloadResponse $payloadResponse
     * @param InputInterface          $input
     * @param OutputInterface                $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully archived channel!');
        } else {
            $this->writeError($output, sprintf('Failed to archive channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
