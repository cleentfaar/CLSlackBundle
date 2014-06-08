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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiSearchAllCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('slack:api:search-all');
        $this->setDescription('Searches your Slack\'s instance for messages and files matching a given query.');
        $this->addArgument(
            'query',
            InputArgument::REQUIRED,
            'The query to search with'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function buildOptions(array $options, InputInterface $input)
    {
        $options['query'] = $input->getArgument('query');

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'search.all';
    }
}
