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

use CL\Slack\Model\Channel;
use CL\Slack\Payload\UsersListPayload;
use CL\Slack\Payload\UsersListPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class UsersListCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:users:list');
        $this->setDescription('Returns a list of all users in the team (including deleted/deactivated users)');
        $this->setHelp(<<<EOT
This command returns a list of all users in the team. This includes deleted/deactivated users.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/users.list</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'users.list';
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersListPayload $payload
     * @param InputInterface      $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        // no configuration needed
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersListPayloadResponse $payloadResponse
     * @param InputInterface              $input
     * @param OutputInterface             $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $users = $payloadResponse->getUsers();
            $output->writeln(sprintf('Received <comment>%d</comment> users...', count($users)));
            if (!empty($users)) {
                $this->renderTable($output, $users, null);
                $this->writeOk($output, 'Successfully listed users');
            } else {
                $this->writeError($output, 'No users seem to be assigned to your team... this is strange...');
            }
        } else {
            $this->writeError($output, sprintf('Failed to list users: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
