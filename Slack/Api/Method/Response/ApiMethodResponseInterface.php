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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
interface ApiMethodResponseInterface
{
    /**
     * @param Response $response
     */
    public function __construct(Response $response);

    /**
     * @return bool
     */
    public function isOk();

    /**
     * @param OutputInterface $output
     * @param Command         $command
     */
    public function toOutput(OutputInterface $output, Command $command);
}
