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

use CL\Slack\Payload\GroupsCreateChildPayload;
use CL\Slack\Payload\GroupsCreateChildPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsCreateChildCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:create-child');
        $this->setDescription('This method creates a child group from an existing group (see `--help`)');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The name of the channel to create (must not exist already)');
        $this->setHelp(<<<EOT
The <info>slack:groups:create-child</info> command takes an existing private group and performs the following steps:

- Renames the existing group (from "example" to "example-archived").
- Archives the existing group.
- Creates a new group with the name of the existing group.
- Adds all members of the existing group to the new group.

This is useful when inviting a new member to an existing group while hiding all previous chat history from them.
In this scenario you can run <info>slack:groups:create-child</info> followed by <info>slack:groups:invite</info>.

The new group will have a special `parent_group` property pointing to the original archived group.
This will only be returned for members of both groups, so will not be visible to any newly invited members.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.createChild</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'groups.createChild';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsCreateChildPayload $payload
     * @param InputInterface           $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setGroupId($input->getArgument('group-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsCreateChildPayloadResponse $payloadResponse
     * @param InputInterface                   $input
     * @param OutputInterface                  $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully created child group!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $data = $this->serializeObjectToArray($payloadResponse->getGroup());
                $this->renderTableKeyValue($output, $data);
            }
        } else {
            $this->writeError($output, sprintf('Failed to create child group: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
