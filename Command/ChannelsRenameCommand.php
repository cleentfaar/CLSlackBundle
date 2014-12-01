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

use CL\Slack\Payload\ChannelsRenamePayload;
use CL\Slack\Payload\ChannelsRenamePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsRenameCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:rename');
        $this->setDescription('Leave a channel (as the user of the token).');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to rename');
        $this->addArgument('name', InputArgument::REQUIRED, 'The new name for this channel');
        $this->setHelp(<<<EOT
The <info>slack:channels:rename</info> command renames a team channel.

The only people who can rename a channel are team admins, or the person that originally created the channel.
Others will receive a "not_authorized" error.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.rename</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.rename';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsRenamePayload $payload
     * @param InputInterface        $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
        $payload->setName($input->getArgument('name'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsRenamePayloadResponse $payloadResponse
     * @param InputInterface                $input
     * @param OutputInterface               $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully renamed channel!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $output->writeln('Renamed channel:');
                $data = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderKeyValueTable($output, $data);
            }
        } else {
            $this->writeError($output, sprintf('Failed to leave channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
