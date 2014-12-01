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

use CL\Slack\Model\Channel;
use CL\Slack\Payload\ChannelsListPayload;
use CL\Slack\Payload\ChannelsListPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsListCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:channels:list');
        $this->setDescription('Returns a list of all channels in your Slack team');
        $this->addOption('exclude-archived', null, InputOption::VALUE_OPTIONAL, 'Don\'t return archived channels.');
        $this->setHelp(<<<EOT
This command returns a list of all channels in your Slack team.
This includes channels the caller is in, channels they are not currently in, and archived channels.
The number of (non-deactivated) members in each channel is also returned.
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'channels.list';
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsListPayload $payload
     * @param InputInterface      $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setExcludeArchived($input->getOption('exclude-archived'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsListPayloadResponse $payloadResponse
     * @param InputInterface              $input
     * @param OutputInterface             $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $channels = $payloadResponse->getChannels();
            $output->writeln(sprintf('Received <comment>%d</comment> channels...', count($channels)));
            if (!empty($channels)) {
                $rows = [];
                foreach ($payloadResponse->getChannels() as $channel) {
                    $row = $this->serializeObjectToArray($channel);
                    $row['purpose'] = !$channel->getPurpose() ?: $channel->getPurpose()->getValue();
                    $row['topic'] = !$channel->getTopic() ?: $channel->getTopic()->getValue();

                    $rows[] = $row;
                }
                $this->renderTable($output, $rows, null);
                $this->writeOk($output, 'Successfully listed channels');
            } else {
                $this->writeError($output, 'No channels seem to be assigned to your team... this is strange...');
            }
        } else {
            $this->writeError($output, sprintf('Failed to list channels: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
