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

use CL\Slack\Payload\StarsListPayload;
use CL\Slack\Payload\StarsListPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class StarsListCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:stars:list');
        $this->setDescription('Returns a list of all the items starred by a user');
        $this->addOption('user-id', null, InputOption::VALUE_REQUIRED, 'Show stars by this user. Defaults to the token\'s user.');
        $this->setHelp(<<<EOT
Returns a list of all the items starred by a user.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/stars.list</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'stars.list';
    }

    /**
     * {@inheritdoc}
     *
     * @param StarsListPayload $payload
     * @param InputInterface   $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        $payload->setUserId($input->getOption('user-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param StarsListPayloadResponse $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $stars = $payloadResponse->getItems();
            $output->writeln(sprintf('Received <comment>%d</comment> starred items...', count($stars)));
            if (!empty($stars)) {
                $this->renderTable($output, $stars, null);
                $this->writeOk($output, 'Finished listing starred items');
            } else {
                $this->writeComment($output, 'No starred items to list');
            }
        } else {
            $this->writeError($output, sprintf(
                'Failed to list starred items: %s',
                $payloadResponse->getErrorExplanation()
            ));
        }
    }
}
