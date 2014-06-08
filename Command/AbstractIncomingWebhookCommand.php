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

use CL\Bundle\SlackBundle\Slack\Payload\Transport\TransportInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractIncomingWebhookCommand extends AbstractCommand
{
    /**
     * @var string|null
     */
    protected $defaultUsername;

    /**
     * @var string|null
     */
    protected $defaultIcon;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addArgument(
            'channel',
            InputArgument::REQUIRED,
            'The Slack channel to send the message to'
        );
        $this->addOption(
            'username',
            'u',
            InputOption::VALUE_REQUIRED,
            'The Slack username that sends the message',
            $this->defaultUsername
        );
        $this->addOption(
            'icon',
            'i',
            InputOption::VALUE_REQUIRED,
            'The icon to display next to the message (surrounded by semi-colons like ":ghost:")',
            $this->defaultIcon
        );
        $this->addOption(
            'dry-run',
            'd',
            InputOption::VALUE_NONE,
            'Debugging option to only see what would be sent to Slack, and not actually send it.'
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $transport = $this->getTransport();
        $channel   = $input->getArgument('channel');
        $message   = $this->createMessage($input);
        $username  = $input->getOption('username');
        $icon      = $input->getOption('icon');
        $options   = [
            'channel' => $channel,
            'text'    => $message,
        ];
        if ($username !== null) {
            $options['username'] = $username;
        }
        if ($icon !== null) {
            $options['icon_emoji'] = $icon;
        }
        $payload = $this->createPayload('incoming_webhook', $options);

        if (false === $input->getOption('dry-run')) {
            $response = $transport->send($payload);

            return $this->report($transport, $payload, $response, $output);
        }

        return $this->reportDry($transport, $payload, $output);
    }

    /**
     * @return TransportInterface
     */
    protected function getTransport()
    {
        return $this->getContainer()->get('cl_slack.incoming_webhook.transport');
    }

    /**
     * @param InputInterface $input
     *
     * @return string
     */
    abstract protected function createMessage(InputInterface $input);
}
