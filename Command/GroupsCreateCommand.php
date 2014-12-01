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

use CL\Slack\Payload\GroupsCreatePayload;
use CL\Slack\Payload\GroupsCreatePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsCreateCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:groups:create');
        $this->setDescription('Creates a new Slack group with the given name');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the private group to create');
        $this->setHelp(<<<EOT
The <info>slack:groups:create</info> command let's you create a new Slack group.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.create</comment>
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethod()
    {
        return 'groups.create';
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsCreatePayload $payload
     * @param InputInterface     $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setName($input->getArgument('name'));
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsCreatePayloadResponse $payloadResponse
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully created group!');
            $this->renderKeyValueTable($output, $payloadResponse->getGroup());
        } else {
            $this->writeError($output, sprintf('Failed to create group: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
