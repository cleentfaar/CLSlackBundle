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

use CL\Bundle\SlackBundle\Slack\Api\Method\ApiMethodFactory;
use CL\Bundle\SlackBundle\Slack\Api\Method\ApiMethodInterface;
use CL\Bundle\SlackBundle\Slack\Api\Method\Response\ApiMethodResponseInterface;
use CL\Bundle\SlackBundle\Slack\Api\Method\Transport\TransportInterface;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractApiCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->addOption(
            'token',
            't',
            InputOption::VALUE_REQUIRED,
            'A token to authenticate with, can be left empty to use the currently configured token.'
        );
        $this->setHelp(sprintf(<<<EOF
These API commands all follow Slack's API documentation as closely as possible.
You can get detailed usage information about the current command with the URL below:

<info>https://api.slack.com/methods/%s</info>

EOF
            , $this->getMethodSlug()));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias    = $this->getMethodAlias();
        $options  = $this->inputToOptions($input);
        $method   = $this->getMethodFactory()->create($alias, $options);
        $request  = $this->getMethodTransport()->getHttpClient()->createRequest('get');
        $response = $this->getMethodTransport()->send($method, $request);

        return $this->report($this->getMethodTransport(), $method, $response, $output);
    }
    /**
     * @param TransportInterface $transport
     * @param ApiMethodInterface $method
     * @param OutputInterface    $output
     *
     * @return int
     */
    protected function reportDry(TransportInterface $transport, ApiMethodInterface $method, OutputInterface $output)
    {
        $url          = $transport->getRequest()->getUrl(false);
        $output->writeln(sprintf('<fg=green>✔</fg=green> Dry-run completed for method: <comment>%s</comment>', $method->getAlias()));
        $output->writeln(sprintf('Would\'ve used the following base URL: <comment>%s</comment>', $url));
        $output->writeln('Would\'ve used the following options:');
        $this->outputOptions($method->getOptions(), $output);

        return 0;
    }

    /**
     * @param TransportInterface         $transport
     * @param ApiMethodInterface         $method
     * @param ApiMethodResponseInterface $response
     * @param OutputInterface            $output
     *
     * @return int
     */
    protected function report(TransportInterface $transport, ApiMethodInterface $method, ApiMethodResponseInterface $response, OutputInterface $output)
    {
        $url          = $transport->getRequest()->getUrl(false);
        $responseBody = $transport->getHttpResponse()->getBody(true);
        if ($responseBody === "ok" || $responseBody === "error") {
            $errorMessage = 'unknown';
            $ok           = false;
        } else {
            $responseBodyArray = (array) json_decode($responseBody);
            $errorMessage      = array_key_exists('error', $responseBodyArray) ? $responseBodyArray['error'] : 'unknown';
            $ok                = $response->isOk();
        }
        switch ($ok) {
            case true:
                if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                    $output->writeln(sprintf('<fg=green>✔</fg=green> Successfully executed API method <comment>%s</comment>', $method->getAlias()));
                }
                $response->toOutput($output, $this);
                $return = 0;
                break;
            default:
                $output->writeln(sprintf('<fg=red>✘</fg=red> Slack did not respond correctly (ok: <comment>%s</comment>)', var_export($ok, true)));
                $output->writeln(sprintf('The error returned was: <error>%s</error>', $errorMessage));
                $return = 1;
                break;
        }
        if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln(sprintf("<comment>URL used: %s</comment>", $url));
            $output->writeln('<comment>Options sent:</comment>');
            $this->outputOptions($method->getOptions(), $output);
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_VERY_VERBOSE) {
                /** @var FormatterHelper $formatterHelper */
                $formatterHelper = $this->getHelper('formatter');
                $output->writeln('The response body was:');
                $output->writeln($formatterHelper->formatBlock($responseBody, 'comment'));
            }
        }

        return $return;
    }

    /**
     * @param array           $options
     * @param OutputInterface $output
     */
    protected function outputOptions(array $options, OutputInterface $output)
    {
        /** @var TableHelper $tableHelper */
        $tableHelper = $this->getHelper('table');
        $tableHelper->setHeaders([
            'Key',
            'Value'
        ]);
        $rows = [];
        foreach ($options as $key => $value) {
            $rows[] = [$key, $value];
        }
        $tableHelper->setRows($rows);
        $tableHelper->render($output);
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
     * @return string
     */
    protected function getConfiguredToken()
    {
        return $this->getContainer()->getParameter('cl_slack.api_token');
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     */
    protected function inputToOptions(InputInterface $input)
    {
        $options = [];
        $options['token'] = $input->getOption('token') ? : $this->getConfiguredToken();

        return $options;
    }

    /**
     * @return \CL\Bundle\SlackBundle\Slack\Api\Method\Transport\Transport
     */
    protected function getMethodTransport()
    {
        return $this->getContainer()->get('cl_slack.api_method_transport');
    }

    /**
     * @return ApiMethodFactory
     */
    protected function getMethodFactory()
    {
        return $this->getContainer()->get('cl_slack.api_method_factory');
    }

    /**
     * @todo Find a way so we only have to define the alias in the service definition itself.
     *       This is currently impossible because we need it's value during configure();
     *       where the container is not yet available
     *
     * @return string
     */
    protected function getMethodAlias()
    {
        return $this->getMethodSlug();
    }

    /**
     * @return string
     */
    abstract protected function getMethodSlug();
}
