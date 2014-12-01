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

use CL\Slack\Payload\GroupsLeavePayload;
use CL\Slack\Payload\GroupsLeavePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsLeaveCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:leave');
        $this->setDescription('Leave a group (as the user of the token).');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of the group to leave');
        $this->setHelp(<<<EOT
The <info>slack:groups:leave</info> command leaves a group as the user of the token.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.leave</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'groups.leave';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsLeavePayload $payload
     * @param InputInterface     $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setGroupId($input->getArgument('group-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsLeavePayloadResponse $payloadResponse
     * @param InputInterface             $input
     * @param OutputInterface            $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully left group!');
        } else {
            $this->writeError($output, sprintf('Failed to leave group: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
