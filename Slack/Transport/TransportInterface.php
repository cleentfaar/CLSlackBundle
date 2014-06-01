<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Transport;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
interface TransportInterface
{
    /**
     * @param string $url
     */
    public function __construct($url);

    /**
     * @return string
     */
    public function getUrl();
}
