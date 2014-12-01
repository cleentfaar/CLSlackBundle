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

use CL\Slack\Model\AbstractModel;
use CL\Slack\Model\Customizable;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use CL\Slack\Transport\ApiClient;
use CL\Slack\Transport\ApiClientEvents;
use CL\Slack\Transport\Events\RequestEvent;
use CL\Slack\Transport\Events\ResponseEvent;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @var array
     */
    private $rawRequest = [];

    /**
     * @var array
     */
    private $rawResponse = [];

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addOption(
            'token',
            't',
            InputOption::VALUE_REQUIRED,
            'Optional token to use during the API-call (defaults to the configured token)'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiClient = $this->getApiClient();
        $payload   = $this->createPayload();

        $this->configureListeners($apiClient, $output);
        $this->configurePayload($payload, $input);

        $response   = $apiClient->send($payload, $input->getOption('token'));
        $returnCode = $this->handleResponse($response, $input, $output);

        if (null === $returnCode) {
            return 0;
        }

        return (int)$returnCode;
    }

    /**
     * @return PayloadInterface
     */
    protected function createPayload()
    {
        return $this->getContainer()->get('cl_slack.payload_registry')->get($this->getMethod());
    }

    /**
     * @return ApiClient
     */
    protected function getApiClient()
    {
        return $this->getContainer()->get('cl_slack.api_client');
    }

    /**
     * @return SerializerInterface
     */
    protected function getSerializer()
    {
        return $this->getContainer()->get('jms_serializer');
    }

    /**
     * @param OutputInterface $output
     */
    protected function renderPayloadResponse(OutputInterface $output)
    {
        $this->renderTableKeyValue($output, $this->rawResponse);
    }

    /**
     * @param OutputInterface $output
     * @param array           $data
     * @param array           $headers
     */
    protected function renderTableKeyValue(OutputInterface $output, array $data, array $headers = [])
    {
        $this->renderKeyValueTable($output, $data, $headers);
    }

    /**
     * @param OutputInterface $output
     * @param array|object    $data
     * @param array           $headers
     */
    protected function renderKeyValueTable(OutputInterface $output, $data, array $headers = [])
    {
        if (is_object($data)) {
            $data = $this->serializeObjectToArray($data);
        }

        $rows = [];
        foreach ($data as $key => $value) {
            $rows[] = [$key, $value];
        }

        $this->renderTable($output, $rows, $headers);
    }

    /**
     * @param OutputInterface $output
     * @param array           $rows
     * @param array|null      $headers
     */
    protected function renderTable(OutputInterface $output, array $rows, $headers = null)
    {
        if (version_compare(Kernel::VERSION, '2.5', '<')) {
            /** @var TableHelper $table */
            $table = $this->getHelper('table');
        } else {
            /** @var Table $table */
            $table = new Table($output);
        }

        if (!empty($headers)) {
            $table->setHeaders($headers);
        } elseif ($headers === null && !empty($rows)) {
            $firstRow = reset($rows);
            if ($firstRow instanceof AbstractModel || $firstRow instanceof PayloadResponseInterface) {
                $firstRow = $this->serializeObjectToArray($firstRow);
            }

            $table->setHeaders(array_keys($firstRow));
        }

        $table->setRows($this->simplifyRows($rows));

        if ($table instanceof TableHelper) {
            $table->setCellRowFormat('<fg=yellow>%s</>');
            $table->render($output);
        } else {
            $style = Table::getStyleDefinition('default');
            $style->setCellRowFormat('<fg=yellow>%s</>');
            $table->render();
        }
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    protected function simplifyRows(array $rows)
    {
        $simplified = [];
        foreach ($rows as $x => $row) {
            $row = $this->formatTableRow($row);
            foreach ($row as $column => $value) {
                if (!is_scalar($value)) {
                    $value = $this->formatNonScalarColumnValue($value);
                }

                $simplified[$x][$column] = $value;
            }
        }

        return $simplified;
    }

    /**
     * @param object|array $row
     *
     * @return array
     */
    protected function formatTableRow($row)
    {
        if (is_object($row)) {
            $row = $this->serializeObjectToArray($row);
        }

        return $row;
    }

    /**
     * @param $nonScalar
     *
     * @return string
     */
    protected function formatNonScalarColumnValue($nonScalar)
    {
        if ($nonScalar instanceof Customizable) {
            $nonScalar = $nonScalar->getValue();
        } else {
            $nonScalar = json_encode($nonScalar);
        }

        return $nonScalar;
    }

    /**
     * @param object $object
     * @param bool   $jsonEncodeNonScalars
     *
     * @return array
     */
    protected function serializeObjectToArray($object, $jsonEncodeNonScalars = true)
    {
        $json = $this->getSerializer()->serialize($object, 'json');
        $data = json_decode($json, true);

        if (!empty($data)) {
            if ($jsonEncodeNonScalars === true) {
                foreach ($data as $key => $value) {
                    if (!is_scalar($value)) {
                        $value = json_encode($value);
                    }
                    $data[$key] = $value;
                }
            }

            return $data;
        }

        return [];
    }

    /**
     * @param OutputInterface $output
     * @param string          $message
     */
    protected function writeOk(OutputInterface $output, $message)
    {
        $output->writeln(sprintf('<fg=green>✔</fg=green> %s', $message));
    }

    /**
     * @param OutputInterface $output
     * @param string          $message
     */
    protected function writeError(OutputInterface $output, $message)
    {
        $output->writeln(sprintf('<fg=red>✘</fg=red> %s', $message));
    }

    /**
     * @param ApiClient       $apiClient
     * @param OutputInterface $output
     */
    private function configureListeners(ApiClient $apiClient, OutputInterface $output)
    {
        $self = $this;

        $apiClient->getEventDispatcher()->addListener(
            ApiClientEvents::EVENT_BEFORE,
            function (RequestEvent $event) use ($output, $self) {
                $self->rawRequest = $event->getRawPayload();
                if ($output->getVerbosity() > OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln('<comment>Debug: sending payload...</comment>');
                    $this->renderKeyValueTable($output, $self->rawRequest);
                }
            }
        );

        $apiClient->getEventDispatcher()->addListener(
            ApiClientEvents::EVENT_AFTER,
            function (ResponseEvent $event) use ($output, $self) {
                $self->rawResponse = $event->getRawPayloadResponse();
                if ($output->getVerbosity() > OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln('<comment>Debug: received payload response...</comment>');
                    $this->renderKeyValueTable($output, $self->rawResponse);
                }
            }
        );
    }

    /**
     * @return string
     */
    abstract protected function getMethod();

    /**
     * @param PayloadResponseInterface $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     *
     * @return int
     */
    abstract protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output);

    /**
     * @param PayloadInterface $payload
     * @param InputInterface   $input
     */
    abstract protected function configurePayload(PayloadInterface $payload, InputInterface $input);
}
