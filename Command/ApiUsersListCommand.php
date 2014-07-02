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

use CL\Slack\Api\Method\UsersListApiMethod;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiUsersListCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('slack:api:users-list');
        $this->setDescription('Returns a list of all users in your Slack team. This includes deleted/deactivated users.');
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodSlug()
    {
        return UsersListApiMethod::getSlug();
    }

    /**
     * @param InputInterface $input
     * @param array          $options
     *
     * @return array
     */
    protected function inputToOptions(InputInterface $input, array $options)
    {
        return $options;
    }
}
