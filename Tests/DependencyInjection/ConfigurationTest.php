<?php


namespace Xtrasmal\TacticianBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;
use Xtrasmal\TacticianBundle\DependencyInjection\Configuration;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    /**
     * Return the instance of ConfigurationInterface that should be used by the
     * Configuration-specific assertions in this test-case
     *
     * @return \Symfony\Component\Config\Definition\ConfigurationInterface
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testBlankConfiguration()
    {
        $this->assertConfigurationIsValid([]);
    }

    public function testSimpleMiddleware()
    {
        $this->assertConfigurationIsValid([
            'tactician' => [
                'commandbus' => [
                    'default' => [
                        'middleware' => [
                            'my_middleware'  => 'some_middleware',
                            'my_middleware2' => 'some_middleware',
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function testMiddlewareMustBeScalar()
    {
        $this->assertConfigurationIsInvalid(
            [
                'tactician' => [
                    'commandbus' => [
                        'default' => [
                            'middleware' => [
                                'my_middleware'  => [],
                                'my_middleware2' => 'some_middleware',
                            ]
                        ]
                    ]
                ]
            ],
            'Invalid type for path "tactician.commandbus.default.middleware.my_middleware". Expected scalar, but got array.'
        );
    }

    public function testDefaultMiddlewareMustExist()
    {
        $this->assertConfigurationIsInvalid(
            [
                'tactician' => [
                    'default_bus' => 'foo',
                    'commandbus' => [
                        'bar' => [
                            'middleware' => [
                                'my_middleware'  => 'some_middleware',
                            ]
                        ]
                    ]
                ]
            ],
            'The default_bus "foo" was not defined as command bus.'
        );

        $this->assertConfigurationIsInvalid(
            [
                'tactician' => [
                    'commandbus' => [
                        'bar' => [
                            'middleware' => [
                                'my_middleware'  => 'some_middleware',
                            ]
                        ]
                    ]
                ]
            ],
            'The default_bus "default" was not defined as command bus.'
        );
    }

    public function testMiddlewareDefinitionCannotBeEmpty()
    {
        $this->assertConfigurationIsInvalid(
            [
                'tactician' => [
                    'commandbus' => [
                        'default' => [
                            'middleware' => [
                            ]
                        ]
                    ]
                ]
            ],
            'The path "tactician.commandbus.default.middleware" should have at least 1 element(s) defined.'
        );

        $this->assertConfigurationIsInvalid(
            [
                'tactician' => [
                    'commandbus' => [
                        'foo' => [
                            'middleware' => [
                            ]
                        ]
                    ]
                ]
            ],
            'The path "tactician.commandbus.foo.middleware" should have at least 1 element(s) defined.'
        );
    }
}