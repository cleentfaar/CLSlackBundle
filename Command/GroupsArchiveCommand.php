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

use CL\Slack\Payload\GroupsArchivePayload;
use CL\Slack\Payload\GroupsArchivePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsArchiveCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:archive');
        $this->setDescription('Archives a given Slack group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of a private group to archive');
        $this->setHelp(<<<EOT
The <info>slack:groups:archive</info> command let's you archive a given Slack group.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.archive</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'groups.archive';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsArchivePayload $payload
     * @param InputInterface       $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setGroupId($input->getArgument('group-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsArchivePayloadResponse $payloadResponse
     * @param InputInterface               $input
     * @param OutputInterface              $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully archived group!');
        } else {
            $this->writeError($output, sprintf('Failed to archive group: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
