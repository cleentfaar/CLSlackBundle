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

use CL\Slack\Payload\EmojiListPayload;
use CL\Slack\Payload\EmojiListPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class EmojiListCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:emoji:list');
        $this->setDescription('Returns a list of all the custom emoji for your Slack team');
        $this->setHelp(<<<EOT
The <info>slack:emoji:list</info> command returns a list of all the custom emoji in your Slack team.

The emoji property contains a map of name/url pairs, one for each custom emoji used by the team.

The alias: <comment>pseudo-protocol</comment> will be used where the emoji is an alias, the string following the colon is
the name of the other emoji this emoji is an alias to.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/emoji.list</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'emoji.list';
    }

    /**
     * {@inheritdoc}
     *
     * @param EmojiListPayload $payload
     * @param InputInterface   $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        // no configuration needed
    }

    /**
     * {@inheritdoc}
     *
     * @param EmojiListPayloadResponse $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $emojis = $payloadResponse->getEmojis();
            $output->writeln(sprintf('Got <comment>%d</comment> emojis...', count($emojis)));
            if (!empty($emojis)) {
                $this->renderKeyValueTable($output, $emojis, ['Name', 'URL']);
            }
        } else {
            $this->writeError($output, sprintf('Failed to fetch emojis: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
