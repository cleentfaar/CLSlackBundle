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

use CL\Slack\Api\Method\SearchAllApiMethod;

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
        return SearchAllApiMethod::getSlug();
    }
}
