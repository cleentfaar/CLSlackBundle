<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Api\Method\Response;

use Guzzle\Http\Message\Response;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class ApiMethodResponse implements ApiMethodResponseInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->data     = json_decode($response->getBody(true), true);
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return (bool) $this->data['ok'];
    }

    /**
     * {@inheritdoc}
     */
    public function toOutput(OutputInterface $output, Command $command)
    {
        $output->writeln(sprintf('OK: <comment>%s</comment>', var_export($this->isOk(), true)));
    }
}
