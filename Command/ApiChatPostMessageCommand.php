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

use CL\Slack\Api\Method\ChatPostMessageMethod;
use CL\Slack\Api\Method\Response\ChatPostMessageResponse;
use CL\Slack\Api\Method\Response\Response;
use CL\Slack\Api\Method\Response\ResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
        $this->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'The username that will send this text');
        $this->addOption('icon-url', null, InputOption::VALUE_REQUIRED, 'The URL to use for showing an icon next to the text');
        $this->addOption('icon-emoji', null, InputOption::VALUE_REQUIRED, 'The Slack icon to use next to the text, overrides icon_url');
    }

    /**
     * {@inheritdoc}
     */
    protected function inputToOptions(InputInterface $input, array $options)
    {
        $options['channel']    = '#' . ltrim($input->getArgument('channel'), '#');
        $options['text']       = $input->getArgument('text');
        $options['icon_url']   = (string) $input->getOption('icon-url');
        $options['icon_emoji'] = $input->getOption('icon-emoji') ? ':' . trim($input->getOption('icon-emoji'), ':') . ':' : '';
        $options['username']   = (string) $input->getOption('username');

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodSlug()
    {
        return ChatPostMessageMethod::getSlug();
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodAlias()
    {
        return ChatPostMessageMethod::getAlias();
    }

    /**
     * @param ChatPostMessageResponse $response
     *
     * {@inheritdoc}
     */
    protected function responseToOutput(ResponseInterface $response, OutputInterface $output)
    {
        $this->renderTableKeyValue(['Timestamp' => $response->getTimestamp()], $output);
    }
}
