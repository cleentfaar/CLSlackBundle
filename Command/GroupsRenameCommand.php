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

use CL\Slack\Payload\GroupsRenamePayload;
use CL\Slack\Payload\GroupsRenamePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsRenameCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:rename');
        $this->setDescription('Leave a group (as the user of the token).');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of the group to rename');
        $this->addArgument('name', InputArgument::REQUIRED, 'The new name for this group');
        $this->setHelp(<<<EOT
The <info>slack:groups:rename</info> command renames a team group.

The only people who can rename a group are team admins, or the person that originally created the group.
Others will receive a "not_authorized" error.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.rename</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'groups.rename';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsRenamePayload $payload
     * @param InputInterface        $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setGroupId($input->getArgument('group-id'));
        $payload->setName($input->getArgument('name'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsRenamePayloadResponse $payloadResponse
     * @param InputInterface                $input
     * @param OutputInterface               $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully renamed group!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $output->writeln('Renamed group:');
                $data = $this->serializeObjectToArray($payloadResponse->getGroup());
                $this->renderKeyValueTable($output, $data);
            }
        } else {
            $this->writeError($output, sprintf('Failed to leave group: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
