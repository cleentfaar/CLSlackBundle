<?php

namespace CL\Bundle\SlackBundle\Tests\DependencyInjection\Compiler;

use CL\Bundle\SlackBundle\DependencyInjection\Compiler\RegisterApiClientPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\ContainerBuilderHasAliasConstraint;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterApiClientPassTest extends AbstractCompilerPassTestCase
{
    const API_CLIENT_ID      = 'cl_slack.api_client';
    const MOCK_API_CLIENT_ID = 'cl_slack.mock_api_client';

    private $apiClientClass;
    private $mockApiClientClass;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->apiClientClass     = 'CL\Slack\Transport\ApiClient';
        $this->mockApiClientClass = 'CL\Slack\Test\Transport\MockApiClient';

        $this->setDefinition(self::API_CLIENT_ID, new Definition($this->apiClientClass));
        $this->setDefinition(self::MOCK_API_CLIENT_ID, new Definition($this->mockApiClientClass));

    }

    /**
     * @test
     */
    public function the_api_client_is_mocked_when_test_mode_is_disabled()
    {
        $this->setParameter('cl_slack.test', true);

        $this->compile();

        $this->assertContainerBuilderHasAlias(self::API_CLIENT_ID, self::MOCK_API_CLIENT_ID);
        $this->assertArrayNotHasKey(self::API_CLIENT_ID, $this->container->getDefinitions());
        $this->assertContainerBuilderHasService(self::MOCK_API_CLIENT_ID, $this->mockApiClientClass);
    }

    /**
     * @test
     */
    public function the_api_client_is_not_mocked_when_test_mode_is_disabled()
    {
        $this->setParameter('cl_slack.test', false);

        $this->compile();

        $this->assertContainerBuilderDoesNotHaveAlias(self::API_CLIENT_ID, $this->mockApiClientClass);
        $this->assertContainerBuilderHasService(self::API_CLIENT_ID, $this->apiClientClass);
        $this->assertContainerBuilderHasService(self::MOCK_API_CLIENT_ID, $this->mockApiClientClass);
    }

    /**
     * @inheritdoc
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterApiClientPass());
    }

    /**
     * @param string $aliasId
     * @param string $expectedServiceId
     */
    private function assertContainerBuilderDoesNotHaveAlias($aliasId, $expectedServiceId)
    {
        self::assertThat(
            $this->container,
            new \PHPUnit_Framework_Constraint_Not(new ContainerBuilderHasAliasConstraint($aliasId, $expectedServiceId))
        );
    }
}
