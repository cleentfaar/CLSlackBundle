<?php

namespace CL\Bundle\SlackBundle\Tests\DependencyInjection\Compiler;

use CL\Bundle\SlackBundle\DependencyInjection\Compiler\RegisterApiClientPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterApiClientPassTest extends AbstractCompilerPassTestCase
{
    public function testApiClientIsMockedWhenTestIsTrue()
    {
        $apiClientId     = 'cl_slack.api_client';
        $mockApiClientId = 'cl_slack.mock_api_client';

        $this->setDefinition($apiClientId, new Definition());
        $this->setDefinition($mockApiClientId, new Definition());
        $this->setParameter('cl_slack.test', true);

        $this->compile();

        $this->assertContainerBuilderHasAlias($apiClientId, $mockApiClientId);
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterApiClientPass());
    }
}
