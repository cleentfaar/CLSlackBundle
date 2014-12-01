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

use CL\Slack\Model\file;
use CL\Slack\Payload\FilesListPayload;
use CL\Slack\Payload\FilesListPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class FilesListCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:files:list');
        $this->setDescription('Returns a list of all files in your Slack team');
        $this->addOption('user-id', 'u', InputOption::VALUE_REQUIRED, 'Filter files created by a single user.');
        $this->addOption('from', null, InputOption::VALUE_REQUIRED, 'Filter files created after this timestamp (inclusive).');
        $this->addOption('to', null, InputOption::VALUE_REQUIRED, 'Filter files created before this timestamp (inclusive).');
        $this->addOption('types', null, InputOption::VALUE_REQUIRED, 'Filter files by type. You can pass multiple values in the types argument, like types=posts,snippets. The default value is all, which does not filter the list.');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of items to return per page.');
        $this->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page number of results to return.');
        $this->setHelp(<<<EOT
The <info>slack:files:list</info> command returns a list of files within the team.
It can be filtered and sliced in various ways.

The response contains a list of files, followed by some paging information.

- Files are always returned with the most recent first.
- Paging contains:
  - the count of files returned
  - the total number of files matching the filter(s) (if any were supplied)
  - the page of results returned in this response
  - the total number of pages available
EOT
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param FilesListPayload $payload
     * @param InputInterface   $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setUserId($input->getOption('user-id'));
        $payload->setCount($input->getOption('count'));
        $payload->setPage($input->getOption('page'));
        $payload->setTimestampFrom($input->getOption('from'));
        $payload->setTimestampTo($input->getOption('to'));
        $payload->setTypes($input->getOption('types'));
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'files.list';
    }

    /**
     * {@inheritdoc}
     *
     * @param FilesListPayloadResponse $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $files = $payloadResponse->getFiles();
            $this->writeOk($output, sprintf('Received <comment>%d</comment> files...', count($files)));
            if (!empty($files)) {
                $output->writeln('Listing files...');
                $this->renderTable($output, $files, null);
            }
            if ($payloadResponse->getPaging()) {
                $output->writeln('Paging...');
                $this->renderKeyValueTable($output, $payloadResponse->getPaging());
            }
        } else {
            $this->writeError($output, sprintf('Failed to list files: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
