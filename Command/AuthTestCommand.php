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

use CL\Slack\Payload\AuthTestPayload;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\AuthTestPayloadResponse;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class AuthTestCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:auth:test');
        $this->setDescription('Test authentication with the Slack API and, optionally, tells you who you are (use -v).');
        $this->setHelp(<<<EOT
The <info>slack:auth:test</info> command lets you test authenticating with the Slack API.

Use the verbose option `-v` to also return information about the token's user.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/auth.test</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'auth.test';
    }

    /**
     * {@inheritdoc}
     *
     * @param AuthTestPayload $payloadResponse
     * @param InputInterface  $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        // no configuration needed
    }

    /**
     * {@inheritdoc}
     *
     * @param AuthTestPayloadResponse $payloadResponse
     * @param InputInterface          $input
     * @param OutputInterface         $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully authenticated by the Slack API!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $data = [
                    'User ID'  => $payloadResponse->getUserId(),
                    'Username' => $payloadResponse->getUsername(),
                    'Team ID'  => $payloadResponse->getTeamId(),
                    'Team'     => $payloadResponse->getTeam(),
                ];
                $this->renderKeyValueTable($output, $data);
            }
        } else {
            $this->writeError($output, sprintf('Failed to be authenticated by the Slack API: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
