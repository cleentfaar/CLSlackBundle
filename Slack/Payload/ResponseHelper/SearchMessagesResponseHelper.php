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
class SearchMessagesResponseHelper extends AbstractSearchResponseHelper
{
    /**
     * @return array
     */
    public function getMessages()
    {
        return (array) $this->responseBody->getCustomData('messages');
    }
}
