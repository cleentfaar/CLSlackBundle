<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Tests\Slack\Payload\Type;

use CL\Bundle\SlackBundle\Slack\Payload\Payload;
use CL\Bundle\SlackBundle\Slack\Payload\Type\SearchAllType;
use CL\Bundle\SlackBundle\Tests\AbstractTestCase;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class SearchAllTypeTest extends AbstractTestCase
{
    public function testQuery()
    {
        $options = [
            'query' => 'this is my query',
        ];
        $payload = $this->getSearchAllMock($options);
        $this->assertEquals(
            $options['query'],
            $payload->getOptions()['query'],
            "The query given beforehand does not match the value returned by the payload"
        );
    }

    /**
     * @dataProvider getValidSortingDirections
     */
    public function testValidSortDirection($sortDirection)
    {
        $options = [
            'query'    => 'this is my query',
            'sort_dir' => $sortDirection,
        ];
        $this->getSearchAllMock($options);

        $this->assertTrue(true, "No exception should be thrown with a valid sorting direction");
    }

    /**
     * @dataProvider getInvalidSortingDirections
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSortDirection($sortDirection)
    {
        $options = [
            'query'    => 'this is my query',
            'sort_dir' => $sortDirection,
        ];
        $this->getSearchAllMock($options);
    }

    /**
     * @dataProvider getPayloads
     */
    public function testToArray(array $payloadArrayBefore, array $payloadArrayAfter)
    {
        $payload = $this->getSearchAllMock($payloadArrayBefore);
        $actual  = $payload->getOptions();
        $this->assertEquals($payloadArrayAfter, $actual, 'Expected payload does not match actual payload');
    }

    /**
     * @return array
     */
    public function getPayloads()
    {
        return [
            [
                [
                    'query'    => 'Testing a query',
                    'sort_dir' => SearchAllType::SORT_DIR_ASC,
                ],
                [
                    'query'     => 'Testing a query',
                    'sort_dir'  => SearchAllType::SORT_DIR_ASC,
                    'highlight' => '0', // @todo GET THIS FIXED ASAP!
                ],
            ],
        ];
    }

    public function getValidSortingDirections()
    {
        return [
            [SearchAllType::SORT_DIR_ASC],
            [SearchAllType::SORT_DIR_DESC],
        ];
    }

    public function getInvalidSortingDirections()
    {
        return [
            ['abc'],
            ['xyz'],
            [123],
        ];
    }

    /**
     * @param array $options
     *
     * @return Payload
     */
    protected function getSearchAllMock(array $options)
    {
        $type    = new SearchAllType();
        $payload = new Payload($type);
        $payload->setOptions($options);

        return $payload;
    }
}
