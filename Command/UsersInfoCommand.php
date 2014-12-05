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

use CL\Slack\Payload\UsersInfoPayload;
use CL\Slack\Payload\UsersInfoPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class UsersInfoCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:users:info');
        $this->setDescription('Returns information about a team member');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'User to get info on');
        $this->setHelp(<<<EOT
The <info>slack:users:info</info> command returns information about a team member.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/users.info</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'users.info';
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersInfoPayload $payload
     * @param InputInterface   $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setUserId($input->getArgument('user-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersInfoPayloadResponse $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->renderKeyValueTable($output, $payloadResponse->getUser());
        } else {
            $this->writeError($output, sprintf('Failed to fetch information about the user: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
