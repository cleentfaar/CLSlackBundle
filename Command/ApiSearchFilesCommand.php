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

use CL\Slack\Api\Method\Response\ResponseInterface;
use CL\Slack\Api\Method\Response\SearchFilesResponse;
use CL\Slack\Api\Method\SearchFilesMethod;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiSearchFilesCommand extends AbstractApiSearchCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('slack:api:search-files');
        $this->setDescription('Searches your Slack\'s instance for files matching a given query.');
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodSlug()
    {
        return SearchFilesMethod::getSlug();
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodAlias()
    {
        return SearchFilesMethod::getAlias();
    }

    /**
     * @param SearchFilesResponse $response
     *
     * {@inheritdoc}
     */
    protected function responseToOutput(ResponseInterface $response, OutputInterface $output)
    {
        $totalFiles = $response->getNumberOfFiles();
        $output->writeln(sprintf('Files found: <comment>%d</comment>', $totalFiles));
        if ($totalFiles > 0) {
            $this->renderTable(['Name', 'Title', 'Type'], $response->getFiles(), $output);
        }
    }
}
