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

use CL\Slack\Payload\ChannelsJoinPayload;
use CL\Slack\Payload\ChannelsJoinPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsJoinCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:join');
        $this->setDescription('Joins a channel with the token\'s user (creates channel if it doesn\'t exist)');
        $this->addArgument('channel', InputArgument::REQUIRED, 'The name of the channel to join (or create if it doesn\'t exist yet)');
        $this->setHelp(<<<EOT
The <info>slack:channels:join</info> command is used to join a channel.
If the channel does not exist, it is created.

Unlike the other channels-commands, this command requires you to supply the NAME instead of the ID of the channel,
because the channel might be created if it doesn't exist yet.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.join</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.join';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsJoinPayload $payload
     * @param InputInterface      $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannel($input->getArgument('channel'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsJoinPayloadResponse $payloadResponse
     * @param InputInterface              $input
     * @param OutputInterface             $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully joined channel!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                if ($payloadResponse->isAlreadyInChannel()) {
                    $output->writeln('Already in channel:');
                } else {
                    $output->writeln('Joined channel:');
                }
                $data = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderKeyValueTable($output, $data);
            }
        } else {
            $this->writeError($output, sprintf('Failed to join channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
