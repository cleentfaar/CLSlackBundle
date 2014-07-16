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
use CL\Slack\Api\Method\Response\SearchAllResponse;
use CL\Slack\Api\Method\SearchAllMethod;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiSearchAllCommand extends AbstractApiSearchCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('slack:api:search-all');
        $this->setDescription('Searches your Slack\'s instance for messages and files matching a given query.');
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodSlug()
    {
        return SearchAllMethod::getSlug();
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodAlias()
    {
        return SearchAllMethod::getAlias();
    }

    /**
     * @param SearchAllResponse $response
     *
     * {@inheritdoc}
     */
    protected function responseToOutput(ResponseInterface $response, OutputInterface $output)
    {
        $totalMessages = $response->getNumberOfMessages();
        $totalFiles    = $response->getNumberOfFiles();
        $output->writeln(sprintf('Messages found: <comment>%d</comment>', $totalMessages));
        if ($totalMessages > 0) {
            $this->renderTable(['User', 'Username', 'Text'], $response->getMessages(), $output);
        }
        $output->writeln(sprintf('Files found: <comment>%d</comment>', $totalFiles));
        if ($totalFiles > 0) {
            $this->renderTable(['Name', 'Title', 'Type'], $response->getFiles(), $output);
        }
    }
}
