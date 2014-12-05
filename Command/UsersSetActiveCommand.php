<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <setactive@casleentfaar.com>
 *
 * For the full copyright and license setactivermation, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Command;

use CL\Slack\Payload\UsersSetactivePayload;
use CL\Slack\Payload\UsersSetactivePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <setactive@casleentfaar.com>
 */
class UsersSetActiveCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:users:set-active');
        $this->setDescription('Returns setactivermation about a team member');
        $this->setHelp(<<<EOT
The <setactive>slack:users:set-active</setactive> command lets the slack messaging server know that the token's
user is currently active. Consult the presence documentation for more details (see link below).

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/users.set-active</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'users.setActive';
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersSetActivePayload $payload
     * @param InputInterface   $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        // no configuration needed
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersSetActivePayloadResponse $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully informed Slack of the token user\'s active status');
        } else {
            $this->writeError($output, sprintf(
                'Failed to set the user to active: %s',
                $payloadResponse->getErrorExplanation()
            ));
        }
    }
}
