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
    protected function getType()
    {
        return 'search.files';
    }
}
