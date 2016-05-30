<?php

namespace CL\Bundle\SlackBundle\Tests\DependencyInjection;

use CL\Bundle\SlackBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    /**
     * @test
     */
    public function it_can_not_be_valid_with_an_api_token_of_the_wrong_type()
    {
        $this->assertConfigurationIsInvalid([
            [
                'api_token' => [],
            ]
        ], 'api_token');

        $this->assertConfigurationIsInvalid([
            [
                'api_token' => new \stdClass(),
            ]
        ], 'api_token');
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
