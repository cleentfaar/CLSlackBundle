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

use CL\Slack\Payload\ChannelsCreatePayload;
use CL\Slack\Payload\ChannelsCreatePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsCreateCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:create');
        $this->setDescription('Creates new Slack channel with the given name');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the channel to create (must not exist already)');
        $this->setHelp(<<<EOT
The <info>slack:channels:create</info> command let's you create a new Slack channel with the given name.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.create</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.create';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsCreatePayload $payload
     * @param InputInterface        $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setName($input->getArgument('name'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsCreatePayloadResponse $payloadResponse
     * @param InputInterface                $input
     * @param OutputInterface               $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully created channel!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $channelData = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderTableKeyValue($output, $channelData);
            }
        } else {
            $this->writeError($output, sprintf('Failed to create channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
