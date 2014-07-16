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

use CL\Slack\Api\Method\MethodFactory;
use CL\Slack\Api\Method\MethodInterface;
use CL\Slack\Api\Method\Response\ResponseInterface;
use CL\Slack\Api\Method\Transport\TransportInterface;
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
        $options  = $this->inputToOptions($input, []);
        $options  = array_merge($options, [
            'token' => $input->getOption('token') ? : $this->getConfiguredToken(),
        ]);
        $method   = $this->getMethodFactory()->create($alias, $options);
        try {
            $response = $this->getMethodTransport()->send($method);
        } catch (\Exception $e) {
            $output->writeln(sprintf('<fg=red>✘</fg=red> Slack did not respond correctly: %s', $e->getMessage()));

            return 1;
        }

        return $this->report($method, $response, $output);
    }

    /**
     * @param MethodInterface   $method
     * @param ResponseInterface $response
     * @param OutputInterface   $output
     *
     * @return int
     */
    protected function report(MethodInterface $method, ResponseInterface $response, OutputInterface $output)
    {
        $output->writeln(sprintf('<fg=green>✔</fg=green> Successfully executed API method <comment>%s</comment>', $method->getAlias()));
        $output->writeln('<comment>Data received:</comment>');
        $this->responseToOutput($response, $output);
        $return = 0;
        if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln('<comment>Options sent:</comment>');
            $this->renderTable(['Key', 'Value'], $method->getOptions(), $output);
        }

        return $return;
    }

    /**
     * @param ResponseInterface $response
     * @param OutputInterface   $output
     */
    abstract protected function responseToOutput(ResponseInterface $response, OutputInterface $output);

    /**
     * Returns the API token as it is defined in your application's configuration.
     *
     * @return string
     */
    protected function getConfiguredToken()
    {
        return $this->getContainer()->getParameter('cl_slack.api_token');
    }

    /**
     * @return TransportInterface
     */
    protected function getMethodTransport()
    {
        return $this->getContainer()->get('cl_slack.api_method_transport');
    }

    /**
     * @return MethodFactory
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
     * Returns the slug related to the current command's API method. Used for the method factory to create the right
     * Method instance and for displaying an URL to the official documentation for this method.
     *
     * @return string
     */
    abstract protected function getMethodSlug();

    /**
     * Overwrite this method in your subclasses to convert input arguments and options
     * to the related API method's options.
     *
     * @param InputInterface $input
     * @param array          $options
     *
     * @return array
     */
    protected function inputToOptions(InputInterface $input, array $options)
    {
        return $options;
    }
}
