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

use CL\Slack\Api\Method\ChatPostMessageApiMethod;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiChatPostMessageCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('slack:api:chat-post-message');
        $this->setDescription('Sends a message to a Slack channel of your choice');
        $this->addArgument('channel', InputArgument::REQUIRED, 'The channel to send the text to');
        $this->addArgument('text', InputArgument::REQUIRED, 'The text to send');
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     */
    protected function inputToOptions(InputInterface $input)
    {
        $options            = parent::inputToOptions($input);
        $options['channel'] = '#' . ltrim($input->getArgument('channel'), '#');
        $options['text']    = $input->getArgument('text');

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodSlug()
    {
        return ChatPostMessageApiMethod::getSlug();
    }
}
