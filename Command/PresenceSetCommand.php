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

use CL\Slack\Payload\PresenceSetPayload;
use CL\Slack\Payload\PresenceSetPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class PresenceSetCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:presence:set');
        $this->setDescription('This method lets you manually override the token user\'s presence value');
        $this->addArgument('presence', InputArgument::REQUIRED, 'Either active or away');
        $this->setHelp(<<<EOT
The <info>slack:presence:set</info> command lets you manually override the token user's presence value.
Consult the presence documentation for more details.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/presence.set</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'presence.set';
    }

    /**
     * {@inheritdoc}
     *
     * @param PresenceSetPayload $payload
     * @param InputInterface     $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setPresence($input->getArgument('presence'));
    }

    /**
     * {@inheritdoc}
     *
     * @param PresenceSetPayloadResponse $payloadResponse
     * @param InputInterface             $input
     * @param OutputInterface            $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully changed presence!');
        } else {
            $this->writeError($output, sprintf('Failed to change presence: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
