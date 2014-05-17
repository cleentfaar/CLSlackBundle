<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Command;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\Response;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractWebhookCommand extends ContainerAwareCommand
{
    /**
     * @var string|null
     */
    protected $defaultUsername;

    /**
     * @var string|null
     */
    protected $defaultChannel;

    /**
     * @var string|null
     */
    protected $defaultIcon;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addOption(
            'username',
            'u',
            InputOption::VALUE_REQUIRED,
            'The Slack username that sends the message',
            $this->defaultUsername
        );
        $this->addOption(
            'channel',
            'c',
            InputOption::VALUE_REQUIRED,
            'The Slack channel to send the message to',
            $this->defaultChannel
        );
        $this->addOption(
            'icon',
            'i',
            InputOption::VALUE_REQUIRED,
            'The icon to display next to the message (can be one of: ghost, ...)',
            $this->defaultIcon
        );
        $this->addOption(
            'dry-run',
            'd',
            InputOption::VALUE_NONE,
            'Debugging option to only see what would be sent to Slack, and not actually send it.'
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token    = $this->getContainer()->getParameter('cl_slack.token');
        $message  = $this->createMessage($input);
        $channel  = $input->getOption('channel');
        $username = $input->getOption('username');
        $icon     = $input->getOption('icon');

        $url     = $this->createUrl($token);
        $payload = $this->createPayload($message, $username, $channel, $icon);

        if (false === $input->getOption('dry-run')) {
            try {
                $response = $this->sendPayload($url, $payload);

                return $this->report($response, $output);
            } catch (ServerErrorResponseException $e) {
                if (null !== $e->getResponse()) {
                    $output->writeln('<error>Failed to send payload, the following response was returned:</error>');
                    $output->writeln($e->getResponse()->getBody(true));
                }

                return 1;
            }
        } else {
            $output->writeln("<comment>Would've sent the following payload:</comment>");
            $output->writeln(sprintf("URL: %s", $url));
            $output->writeln("Fields: ");
            foreach ($payload as $key => $value) {
                $output->writeln("\t" . $key . ": " . $value);
            }

            return 0;
        }
    }

    /**
     * @param Response        $response
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function report($response = null, OutputInterface $output)
    {
        if (null === $response) {
            $output->writeln("No response was returned");

            return 1;
        }

        $statusCode = $response->getStatusCode();
        switch ($statusCode) {
            case 200:
                $output->writeln("Successfully sent payload to Slack");

                return 0;
            default:
                $output->writeln(sprintf("Slack returned an unexpected status-code (%d)", $statusCode));

                return 1;
        }
    }

    /**
     * @param string $message
     * @param array  $variables
     *
     * @return string
     */
    protected function parseMessage($message, array $variables = [])
    {
        $search  = [];
        $replace = [];
        foreach ($variables as $key => $value) {
            $search[]  = sprintf('{{ %s }}', $key);
            $replace[] = $value;
        }

        return str_replace($search, $replace, $message);
    }

    /**
     * @param $token
     *
     * @return string
     */
    protected function createUrl($token)
    {
        return sprintf('https://treehouselabs.slack.com/services/hooks/incoming-webhook?token=%s', $token);
    }

    /**
     * @param string $text
     * @param null   $username
     * @param null   $channel
     * @param null   $icon
     *
     * @return array
     */
    protected function createPayload($text, $username = null, $channel = null, $icon = null)
    {
        $payload         = [];
        $payload['text'] = $text;
        if (null !== $channel) {
            $payload['channel'] = $channel;
        }
        if (null !== $username) {
            $payload['username'] = $username;
        }
        if (null !== $icon) {
            $payload['icon_emoji'] = $icon; // ghost, ... ?
        }

        return $payload;
    }

    /**
     * @param string $url
     * @param array  $payload
     *
     * @return \Guzzle\Http\Message\Response
     */
    protected function sendPayload($url, array $payload)
    {
        $client  = new Client();
        $request = $client->post(
            $url,
            [
                'content-type' => 'application/json',
            ]
        );
        $request->setBody(json_encode($payload));

        return $client->send($request);
    }

    /**
     * @param InputInterface $input
     *
     * @return string
     */
    abstract protected function createMessage(InputInterface $input);
}
