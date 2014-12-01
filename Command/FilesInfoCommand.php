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

use CL\Slack\Payload\FilesInfoPayload;
use CL\Slack\Payload\FilesInfoPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class FilesInfoCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:files:info');
        $this->setDescription('Returns information about a file in your Slack team');
        $this->addArgument('file-id', InputArgument::REQUIRED, 'The ID of the file to get information on');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of items to return per page.');
        $this->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page number of results to return.');
        $this->setHelp(<<<EOT
The <info>slack:files:info</info> command returns information about a file in your team.

Each comment object in the comments array contains details about a single comment. Comments are returned oldest first.

The paging information contains the count of comments returned, the total number of comments, the page of results
returned in this response and the total number of pages available.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/files.info</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'files.info';
    }

    /**
     * {@inheritdoc}
     *
     * @param FilesInfoPayload $payload
     * @param InputInterface   $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setFileId($input->getArgument('file-id'));
        $payload->setCount($input->getOption('count'));
        $payload->setPage($input->getOption('page'));
    }

    /**
     * {@inheritdoc}
     *
     * @param FilesInfoPayloadResponse $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $file = $payloadResponse->getFile();
            $this->renderKeyValueTable($output, $file);
        } else {
            $this->writeError($output, sprintf('Failed to fetch information about the file: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
