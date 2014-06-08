<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload\ResponseHelper;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class AbstractSearchResponseHelper extends ResponseHelper
{
    /**
     * @return string|null
     */
    public function getQuery()
    {
        return $this->responseBody->getCustomData('query');
    }
}
