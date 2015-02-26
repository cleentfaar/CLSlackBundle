<?php

namespace CL\Bundle\SlackBundle\Tests\DependencyInjection\Compiler;

use CL\Bundle\SlackBundle\CLSlackBundle;
use CL\Bundle\SlackBundle\DependencyInjection\Compiler\RegisterApiClientPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBundleRegistersCompilerPasses()
    {
        $found     = false;
        $container = new ContainerBuilder();
        $bundle    = new CLSlackBundle();
        $bundle->build($container);

        foreach ($container->getCompilerPassConfig()->getPasses() as $pass) {
            if ($pass instanceof RegisterApiClientPass) {
                $found = true;

                break;
            }
        }

        $this->assertTrue($found);
    }
}
