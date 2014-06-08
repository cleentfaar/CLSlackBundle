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

use CL\Bundle\SlackBundle\Slack\Transport\ApiTransport;
use Guzzle\Http\Message\Response;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('slack:api');
        $this->setDescription('Sends a payload to the given API method and shows the returned response');
        $this->addArgument(
            'method',
            InputArgument::REQUIRED,
            'The API method to get a result from (e.g. search.all)'
        );
        $this->addOption(
            'payload',
            'p',
            InputOption::VALUE_REQUIRED,
            'String of parameters in the form of a query (e.g foo=bar&apple=pear). '.
            'Check out the Slack API documentation to see which are required for each method.'
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $typeAlias = $input->getArgument('method');
        $options   = $this->stringToOptions($input->getOption('payload'));
        $payload   = $this->createPayload($typeAlias, $options);
        $response  = $this->getTransport()->send($payload);

        return $this->report($this->getTransport()->getUrl(), $payload, $response, $output);
    }

    /**
     * @param string $string
     *
     * @return array
     */
    protected function stringToOptions($string)
    {
        parse_str($string, $options);

        return $options;
    }

    /**
     * {@inheritdoc}
     *
     * @return ApiTransport
     */
    protected function getTransport()
    {
        return $this->getContainer()->get('cl_slack.api.transport');
    }
}
