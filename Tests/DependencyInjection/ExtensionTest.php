<?php

namespace CL\Bundle\SlackBundle\Tests\DependencyInjection;

use CL\Bundle\SlackBundle\DependencyInjection\CLSlackExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class ExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_sets_the_expected_parameters()
    {
        $this->load([
            'api_token' => '1234',
            'test'      => true,
        ]);

        $this->assertContainerBuilderHasParameter('cl_slack.api_token', '1234');
        $this->assertContainerBuilderHasParameter('cl_slack.test', true);
    }

    /**
     * @test
     */
    public function it_loads_the_expected_services()
    {
        $this->load([
            'api_token' => '1234',
        ]);

        $this->assertContainerBuilderHasService('cl_slack.api_client', 'CL\Slack\Transport\ApiClient');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('cl_slack.api_client', 0, '%cl_slack.api_token%');

        $this->assertContainerBuilderHasService('cl_slack.mock_api_client', 'CL\Slack\Test\Transport\MockApiClient');
        $this->assertContainerBuilderHasService('cl_slack.payload_serializer', 'CL\Slack\Serializer\PayloadSerializer');
        $this->assertContainerBuilderHasService('cl_slack.payload_response_serializer', 'CL\Slack\Serializer\PayloadResponseSerializer');
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [new CLSlackExtension()];
    }
}
