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

use CL\Slack\Payload\OauthAccessPayload;
use CL\Slack\Payload\OauthAccessPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class OauthAccessCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:oauth:access');
        $this->setDescription('Exchange a temporary OAuth code for an API access token');
        $this->addArgument('client-id', InputArgument::REQUIRED, 'Issued when you created your application');
        $this->addArgument('client-secret', InputArgument::REQUIRED, 'Issued when you created your application');
        $this->addArgument('code', InputArgument::REQUIRED, 'The code param returned via the OAuth callback');
        $this->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED, 'This must match the originally submitted URI (if one was sent)');
        $this->setHelp(<<<EOT
The <info>slack:oauth:access</info> command allows you to exchange a temporary OAuth code for an API access token.
This is used as part of the OAuth authentication flow.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/oauth.access</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'oauth.access';
    }

    /**
     * {@inheritdoc}
     *
     * @param OauthAccessPayload $payload
     * @param InputInterface      $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setClientId($input->getArgument('client-id'));
        $payload->setClientSecret($input->getArgument('client-secret'));
        $payload->setCode($input->getArgument('code'));
        $payload->setRedirectUri($input->getOption('redirect-uri'));
    }

    /**
     * {@inheritdoc}
     *
     * @param OauthAccessPayloadResponse $payloadResponse
     * @param InputInterface              $input
     * @param OutputInterface             $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully authenticated through oauth!');
            $output->writeln('Access token: <comment>%s</comment>', $payloadResponse->getAccessToken());
            $output->writeln('Scope: <comment>%s</comment>', $payloadResponse->getScope());
        } else {
            $this->writeError($output, sprintf('Failed to be authenticated through oauth: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
