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

use CL\Slack\Payload\SearchMessagesPayload;
use CL\Slack\Payload\SearchMessagesPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class SearchMessagesCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:search:messages');
        $this->setDescription('Searches messages and files within your Slack team');
        $this->addArgument('query', InputArgument::REQUIRED, 'Search query. May contains booleans, etc.');
        $this->addOption('sort', null, InputOption::VALUE_REQUIRED, 'Return matches sorted by either score or timestamp');
        $this->addOption('sort-dir', null, InputOption::VALUE_REQUIRED, 'Change sort direction to ascending (asc) or descending (desc)');
        $this->addOption('highlight', null, InputOption::VALUE_REQUIRED, 'Pass a value of 1 to enable query highlight markers');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of items to return per page');
        $this->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page number of results to return');
        $this->setHelp(<<<EOT
The <info>slack:search:messages</info> command allows you to search for messages matching a given query

If the `--highlight` option is specified, the matching query terms will be marked up in the results so that clients may
replace them with appropriate highlighting markers (e.g. <span class="highlight"></span>).

The UTF-8 markers used are:
- start: "\xEE\x80\x80"; # U+E000 (private-use)
- end  : "\xEE\x80\x81"; # U+E001 (private-use)

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/search.messages</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'search.messages';
    }

    /**
     * {@inheritdoc}
     *
     * @param SearchMessagesPayload $payload
     * @param InputInterface   $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setQuery($input->getArgument('query'));
        $payload->setSort($input->getOption('sort'));
        $payload->setSortDir($input->getOption('sort-dir'));
        $payload->setPage($input->getOption('page'));
        $payload->setCount($input->getOption('count'));
        $payload->setHighlight($input->getOption('highlight'));
    }

    /**
     * {@inheritdoc}
     *
     * @param SearchMessagesPayloadResponse $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $total = 0;
            if ($messageSearchResult = $payloadResponse->getMessageSearchResult()) {
                $total += $messageSearchResult->getTotal();
            }

            $this->writeComment($output, sprintf('Got %d results...', $total));

            if ($total > 0) {
                $this->writeComment($output, 'Listing messages...');
                if ($messageSearchResult->getTotal() > 1) {
                    $this->renderTable($output, $messageSearchResult->getMatches());
                } else {
                    $this->writeComment($output, 'No messages matched the query');
                }
            }
        } else {
            $this->writeError($output, sprintf('Failed to search: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
