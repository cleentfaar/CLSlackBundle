<?php

namespace CL\Bundle\SlackBundle\Tests\DependencyInjection;

use CL\Bundle\SlackBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    public function testValuesAreValidWithTokenOrNull()
    {
        $this->assertConfigurationIsValid(
            [
                [
                    'api_token' => '1234',
                    'test'      => true,
                ]
            ]
        );

        $this->assertConfigurationIsValid(
            [
                [
                    'api_token' => null,
                    'test'      => false,
                ]
            ]
        );

        $this->assertConfigurationIsValid(
            [
                []
            ]
        );
    }

    public function testValuesAreInvalidIfTypeOfApiTokenIsInvalid()
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
