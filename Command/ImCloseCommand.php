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

use CL\Slack\Payload\ImClosePayload;
use CL\Slack\Payload\ImClosePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ImCloseCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:im:close');
        $this->setDescription('Closes a given Slack im');
        $this->addArgument('im-id', InputArgument::REQUIRED, 'The ID of an IM-channel to close');
        $this->setHelp(<<<EOT
The <info>slack:im:close</info> command let's you close a IM channel

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/im.close</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'im.close';
    }

    /**
     * {@inheritdoc}
     *
     * @param ImClosePayload $payload
     * @param InputInterface $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setImId($input->getArgument('im-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ImClosePayloadResponse $payloadResponse
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->isAlreadyClosed()) {
                $output->writeln('<comment>Couldn\'t close IM channel: the channel has already been closed</comment>');
            } else {
                $this->writeOk($output, 'Successfully closed IM channel!');
            }
        } else {
            $this->writeError($output, sprintf('Failed to close IM channel: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
