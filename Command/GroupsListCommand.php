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

use CL\Slack\Payload\GroupsListPayload;
use CL\Slack\Payload\GroupsListPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsListCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:list');
        $this->setDescription('Returns a list of all groups in your Slack team');
        $this->addOption('exclude-archived', null, InputOption::VALUE_OPTIONAL, 'Don\'t return archived groups.');
        $this->setHelp(<<<EOT
This method returns a list of groups in the team that the caller is in and archived groups that the caller was in.
The list of (non-deactivated) members in each group is also returned.
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'groups.list';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsListPayload $payload
     * @param InputInterface      $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setExcludeArchived($input->getOption('exclude-archived'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsListPayloadResponse $payloadResponse
     * @param InputInterface              $input
     * @param OutputInterface             $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $groups = $payloadResponse->getGroups();
            $output->writeln(sprintf('Received <comment>%d</comment> groups...', count($groups)));
            if (!empty($groups)) {
                $rows = [];
                foreach ($payloadResponse->getGroups() as $group) {
                    $row = $this->serializeObjectToArray($group);
                    $row['purpose'] = !$group->getPurpose() ?: $group->getPurpose()->getValue();
                    $row['topic'] = !$group->getTopic() ?: $group->getTopic()->getValue();

                    $rows[] = $row;
                }
                $this->renderTable($output, $rows, null);
                $this->writeOk($output, 'Finished listing groups');
            } else {
                $this->writeComment($output, 'No groups to list');
            }
        } else {
            $this->writeError($output, sprintf('Failed to list groups: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
