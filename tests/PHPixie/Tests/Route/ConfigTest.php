<?php

namespace PHPixie\Tests\Route;

use PHPixie\Route\RouteConfig;

class ConfigTest extends \PHPixie\Test\Testcase
{
    public function testConfig()
    {
        $data = RouteConfig::config()
            ->translator(RouteConfig::translator()
                ->basePath("path")
                ->baseHost("host")
            )
            ->resolver(RouteConfig::group()
                ->defaults(array('a' => 1))
                ->add("default", RouteConfig::mount()
                    ->name("mount")
                )
                ->add("prefix", RouteConfig::prefix()
                    ->defaults(array('b' => 1))
                    ->resolver(RouteConfig::pattern()
                        ->defaults(array('c' => 1))
                        ->host("host")
                        ->path("path")
                        ->methods(array('GET'))
                        ->attributePatterns(array('id' => 'c'))
                    )
                )
            )->params();

        $this->assertEquals(array(
            'translator' => array(
                'basePath' => 'path',
                'baseHost' => 'host'
            ),
            'resolver' => array(
                'type' => 'group',
                'defaults' => array('a' => 1),
                'resolvers' => array(
                    'default' => array(
                        'type' => 'mount',
                        'name' => 'mount'
                    ),
                    'prefix' => array(
                        'type' => 'prefix',
                        'defaults' => array('b' => 1),
                        'resolver' => array(
                            'type' => 'pattern',
                            'host' => 'host',
                            'path' => 'path',
                            'defaults' => array('c' => 1),
                            'attributePatterns' => array('id' => 'c'),
                            'methods' => array('GET')
                        )
                    )
                )
            )
        ), $data);
    }
}