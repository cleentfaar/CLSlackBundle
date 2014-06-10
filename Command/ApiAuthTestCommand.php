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

use CL\Bundle\SlackBundle\Slack\Api\Method\AuthTestApiMethod;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiAuthTestCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('slack:api:auth-test');
        $this->setDescription('Allows you to test authentication with the Slack API.');
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodSlug()
    {
        return AuthTestApiMethod::getSlug();
    }
}
