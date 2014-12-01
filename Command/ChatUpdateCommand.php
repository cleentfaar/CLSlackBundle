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

use CL\Slack\Payload\ChatUpdatePayload;
use CL\Slack\Payload\ChatUpdatePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChatUpdateCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:chat:update');
        $this->setDescription('Updates a message from a given channel');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel containing the message to be updated');
        $this->addArgument('timestamp', InputArgument::REQUIRED, 'Timestamp of the message to be updated');
        $this->addArgument('message', InputArgument::REQUIRED, 'New text for the message, using the default formatting rules');
        $this->setHelp(<<<EOT
The <info>slack:chat:update</info> command updates a message from a given channel.

The new message uses the default formatting rules, which can be found here: <comment>https://api.slack.com/docs/formatting</comment>

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/chat.update</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'chat.update';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChatUpdatePayload $payload
     * @param InputInterface    $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setChannelId($input->getArgument('channel-id'));
        $payload->setTimestamp($input->getArgument('timestamp'));
        $payload->setMessage($input->getArgument('message'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChatUpdatePayloadResponse $payloadResponse
     * @param InputInterface            $input
     * @param OutputInterface           $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully updated message!');
        } else {
            $this->writeError($output, sprintf('Failed to update message: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
