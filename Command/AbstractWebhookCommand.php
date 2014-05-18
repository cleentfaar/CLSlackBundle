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

use CL\Bundle\SlackBundle\Slack\Webhook\Payload;
use CL\Bundle\SlackBundle\Slack\Webhook\Transport;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\Response;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
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
        $this->addArgument(
            'channel',
            InputArgument::REQUIRED,
            'The Slack channel to send the message to'
        );
        $this->addOption(
            'username',
            'u',
            InputOption::VALUE_REQUIRED,
            'The Slack username that sends the message',
            $this->defaultUsername
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
        /** @var Transport $transport */
        $transport = $this->getContainer()->get('cl_slack.payload.transport');

        $channel  = '#' . $input->getArgument('channel');
        $message  = $this->createMessage($input);
        $username = $input->getOption('username');
        $icon     = $input->getOption('icon');
        $payload  = $this->createPayload($channel, $message, $username, $icon);

        if (false === $input->getOption('dry-run')) {
            try {
                $response = $transport->send($payload);

                return $this->report($response, $output);
            } catch (ServerErrorResponseException $e) {
                if (null !== $e->getResponse()) {
                    $output->writeln('<error>Failed to send payload, the following response was returned:</error>');
                    $output->writeln($e->getResponse()->getBody(true));
                }

                return 1;
            }
        }

        return $this->reportDry($transport->getUrl(), $payload, $output);
    }

    /**
     * @param string          $url
     * @param Payload         $payload
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function reportDry($url, Payload $payload, OutputInterface $output)
    {
        $output->writeln("<comment>Would've sent the following payload:</comment>");
        $output->writeln(sprintf("URL: <comment>%s</comment>", $url));
        foreach ($payload->toArray() as $key => $value) {
            $output->writeln("\t" . $key . ": " . $value);
        }

        return 0;
    }

    /**
     * @param Response        $response
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function report($response, OutputInterface $output)
    {
        $statusCode = $response->getStatusCode();
        switch ($statusCode) {
            case 200:
                $output->writeln("Successfully sent payload to Slack");

                return 0;
            default:
                $output->writeln(sprintf("Slack returned an unexpected status-code (%d)", $statusCode));
                $output->writeln(sprintf("The response body was:\n %s", $response->getBody(true)));

                return 1;
        }
    }

    /**
     * @param string   $message
     * @param string[] $variables
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
     * @param string $text
     * @param string $channel
     * @param null   $username
     * @param null   $icon
     *
     * @return Payload
     */
    protected function createPayload($channel, $text, $username = null, $icon = null)
    {
        $payload = new Payload($channel, $text);
        if (null !== $username) {
            $payload->setUsername($username);
        }
        if (null !== $icon) {
            $payload->setIcon($icon);
        }

        return $payload;
    }

    /**
     * @param InputInterface $input
     *
     * @return string
     */
    abstract protected function createMessage(InputInterface $input);
}
