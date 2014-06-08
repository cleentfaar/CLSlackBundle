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

use CL\Bundle\SlackBundle\Slack\Payload\ResponseHelper\SearchAllResponseHelper;
use Guzzle\Http\Message\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * This payload allows you to search in Slack's messages and files.
 *
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class SearchAllType extends AbstractApiType
{
    const SORT_SCORE     = 'score';
    const SORT_TIMESTAMP = 'timestamp';

    const SORT_DIR_ASC  = 'asc';
    const SORT_DIR_DESC = 'desc';

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['query']);
        $resolver->setOptional([
            'sort',
            'sort_dir',
            'highlight',
            'count',
            'page',
        ]);
        $resolver->setAllowedTypes([
            'query'     => ['string'],
            'sort'      => ['string'],
            'highlight' => ['string'],
            'count'     => ['integer'],
            'page'      => ['integer'],
        ]);
        $resolver->setAllowedValues([
            'sort'      => [self::SORT_SCORE, self::SORT_TIMESTAMP],
            'sort_dir'  => [self::SORT_DIR_ASC, self::SORT_DIR_DESC],
            'highlight' => ['1', '0'],
        ]);
        $resolver->setNormalizers([
            'highlight' => function ($value) {
                return $value === true ? '1' : '0';
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function createResponseHelper(Response $response)
    {
        return new SearchAllResponseHelper($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodSlug()
    {
        return 'search.all';
    }
}
