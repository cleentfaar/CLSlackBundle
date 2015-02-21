<?php

namespace CL\Bundle\SlackBundle\Tests\DependencyInjection\Compiler;

use CL\Bundle\SlackBundle\DependencyInjection\Compiler\RegisterApiClientPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterApiClientPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterApiClientPass());
    }
    /**
     * @test
     */
    public function testApiClientIsMockedWhenTestIsTrue()
    {
        $collectingService = new Definition();
        $this->setDefinition('cl_slack.api_client', $collectingService);
        $this->setParameter('cl_slack.test', true);
        $this->setParameter('cl_slack.mock_api_client.class', 'TestClass');
        $this->compile();

        $this->assertContainerBuilderHasService('cl_slack.api_client', 'TestClass');
    }
}
