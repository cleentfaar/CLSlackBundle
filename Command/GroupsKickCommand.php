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

use CL\Slack\Payload\GroupsKickPayload;
use CL\Slack\Payload\GroupsKickPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsKickCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:kick');
        $this->setDescription('Removes (kicks) a given user from a group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of the group to remove the user from');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'The ID of the user to remove');
        $this->setHelp(<<<EOT
The <info>slack:groups:kick</info> command allows you to remove another member from a grouo.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.kick</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'groups.kick';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsKickPayload $payload
     * @param InputInterface    $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setGroupId($input->getArgument('group-id'));
        $payload->setUserId($input->getArgument('user-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsKickPayloadResponse $payloadResponse
     * @param InputInterface            $input
     * @param OutputInterface           $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully kicked user from the group!');
        } else {
            $this->writeError($output, sprintf('Failed to kick user from the group: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
