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

use CL\Slack\Payload\ChannelsArchivePayloadResponse;
use CL\Slack\Payload\ChannelsInfoPayload;
use CL\Slack\Payload\ChannelsInfoPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsInfoCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:info');
        $this->setDescription('Returns information about a team channel.');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to get information on');
        $this->setHelp(<<<EOT
The <info>slack:channels:info</info> command returns information about a given channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.info</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.info';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsInfoPayload $payload
     * @param InputInterface      $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsInfoPayloadResponse $payloadResponse
     * @param InputInterface                 $input
     * @param OutputInterface                $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $data = $this->serializeObjectToArray($payloadResponse->getChannel());
            $this->renderKeyValueTable($output, $data);
            $this->writeOk($output, 'Successfully retrieved information about the channel!');
        } else {
            $this->writeError($output, sprintf('Failed to retrieve information about the channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
