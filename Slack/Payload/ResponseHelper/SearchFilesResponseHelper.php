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
class SearchFilesResponseHelper extends AbstractSearchResponseHelper
{
    /**
     * @return array
     */
    public function getFiles()
    {
        return (array) $this->responseBody->getCustomData('files');
    }
}
