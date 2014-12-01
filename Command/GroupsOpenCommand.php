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

use CL\Slack\Payload\GroupsOpenPayload;
use CL\Slack\Payload\GroupsOpenPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsOpenCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:open');
        $this->setDescription('Opens a given Slack group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of a private group to open');
        $this->setHelp(<<<EOT
The <info>slack:groups:open</info> command let's you open a given Slack group.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.open</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'groups.open';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsOpenPayload $payload
     * @param InputInterface       $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setGroupId($input->getArgument('group-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsOpenPayloadResponse $payloadResponse
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->isAlreadyOpen()) {
                $output->writeln('<comment>Couldn\'t open group: the group has already been opened</comment>');
            } else {
                $this->writeOk($output, 'Successfully opened group!');
            }
        } else {
            $this->writeError($output, sprintf('Failed to open group: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
