<?php

namespace CL\Bundle\SlackBundle\Tests\DependencyInjection;

use CL\Bundle\SlackBundle\DependencyInjection\CLSlackExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Reference;

class ExtensionTest extends AbstractExtensionTestCase
{
    public function testParameters()
    {
        $this->load([
            'api_token' => '1234',
        ]);

        $this->assertContainerBuilderHasParameter('cl_slack.api_token', '1234');
    }

    public function testServiceDefinitions()
    {
        $this->load([
            'api_token' => '1234',
        ]);

        $this->assertContainerBuilderHasService('cl_slack.api_client', 'CL\Slack\Transport\ApiClient');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('cl_slack.api_client', 0, '%cl_slack.api_token%');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('cl_slack.api_client', 1, new Reference('cl_slack.payload_serializer'));

        $this->assertContainerBuilderHasService('cl_slack.payload_factory', 'CL\Slack\Util\PayloadFactory');

        $this->assertContainerBuilderHasService('cl_slack.payload_serializer', 'CL\Slack\Util\PayloadSerializer');
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [new CLSlackExtension()];
    }
}
