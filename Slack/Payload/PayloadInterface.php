<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload;

use CL\Bundle\SlackBundle\Slack\Payload\Type\TypeInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
interface PayloadInterface
{
    /**
     * @param TypeInterface $type
     */
    public function __construct(TypeInterface $type);

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return TypeInterface
     */
    public function getType();
}
