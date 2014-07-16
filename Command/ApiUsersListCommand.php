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
use CL\Slack\Api\Method\Response\UsersListResponse;
use CL\Slack\Api\Method\UsersListMethod;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        return UsersListMethod::getSlug();
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodAlias()
    {
        return UsersListMethod::getAlias();
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

    /**
     * @param UsersListResponse $response
     *
     * {@inheritdoc}
     */
    protected function responseToOutput(ResponseInterface $response, OutputInterface $output)
    {
        $members     = $response->getMembers();
        $output->writeln(sprintf('Members found: <comment>%d</comment>', count($members)));
        if ($members > 0) {
            $this->renderTable([
                    'ID',
                    'Name',
                    'Deleted',
                    'Color',
                    'Profile',
                    'Is admin',
                    'Is owner',
                    'Has files',
                ],
                $members,
                $output
            );
        }
    }
}
