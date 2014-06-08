<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload\Type\Api;

use CL\Bundle\SlackBundle\Slack\Payload\ResponseHelper\SearchFilesResponseHelper;
use Guzzle\Http\Message\Response;

/**
 * This payload allows you to search in Slack's messages and files.
 *
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class SearchFilesType extends AbstractSearchType
{
    /**
     * {@inheritdoc}
     */
    public function createResponseHelper(Response $response)
    {
        return new SearchFilesResponseHelper($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodSlug()
    {
        return 'search.files';
    }
}
