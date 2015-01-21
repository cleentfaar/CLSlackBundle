<?php

namespace CL\Bundle\SlackBundle\Tests;

use CL\Bundle\SlackBundle\DependencyInjection\CLSlackExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class ExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function testParameters()
    {
        $this->load([
            'api_token' => '1234',
        ]);

        $this->assertContainerBuilderHasParameter('cl_slack.api_token', '1234');
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [new CLSlackExtension()];
    }
}
