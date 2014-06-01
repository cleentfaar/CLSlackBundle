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

use CL\Bundle\SlackBundle\Slack\Payload\Payload;
use CL\Bundle\SlackBundle\Slack\Payload\PayloadFactory;
use CL\Bundle\SlackBundle\Slack\Payload\PayloadInterface;
use CL\Bundle\SlackBundle\Slack\Transport\TransportInterface;
use Guzzle\Http\Message\Response;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @param string           $url
     * @param PayloadInterface $payload
     * @param OutputInterface  $output
     *
     * @return int
     */
    protected function reportDry($url, PayloadInterface $payload, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>Dry-run completed for URL: %s</comment>", $url));
        $output->writeln("<comment>Would've sent the following payload:</comment>");
        $output->writeln(var_export($payload->getOptions(), true));

        return 0;
    }

    /**
     * @param string           $url
     * @param PayloadInterface $payload
     * @param Response         $response
     * @param OutputInterface  $output
     *
     * @return int
     */
    protected function report($url, $payload, $response, OutputInterface $output)
    {
        $responseBody = $response->getBody(true);
        if ($responseBody === "ok" || $responseBody === "error") {
            $status = $responseBody === "ok" ? true : false;
            $errorMessage = 'unknown';
        } else {
            $responseBody = (array) json_decode($responseBody);
            $status = (bool) $responseBody['ok'];
            $errorMessage = array_key_exists('error', $responseBody) ? $responseBody['error'] : 'unknown';
        }
        switch ($status) {
            case true:
                $output->writeln("<fg=green>✔</fg=green> Successfully sent payload to Slack");
                $return = 0;
                break;
            default:
                $output->writeln(sprintf("<fg=red>✘</fg=red> Slack did not respond correctly (parameter 'ok' = %s)", var_export($status, true)));
                $output->writeln(sprintf("The error returned was: <error>%s</error>", $errorMessage));
                $return = 1;
                break;
        }
        if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln(sprintf("<comment>URL used for sending the payload: %s</comment>", $url));
            $output->writeln("<comment>Actual payload sent:</comment>");
            $output->writeln(var_export((array) $payload->getOptions(), true));
            $output->writeln("<comment>The response body was:</comment>");
            var_dump($response->getBody(true));
        }

        return $return;
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
     * @param string $typeAlias
     * @param array  $options
     *
     * @return Payload
     */
    protected function createPayload($typeAlias, array $options)
    {
        $payload = $this->getPayloadFactory()->create($typeAlias, $options);

        return $payload;
    }

    /**
     * @return PayloadFactory
     */
    protected function getPayloadFactory()
    {
        return $this->getContainer()->get('cl_slack.payload_factory');
    }

    /**
     * @return TransportInterface
     */
    abstract protected function getTransport();
}
