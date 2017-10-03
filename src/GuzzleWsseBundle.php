<?php

namespace EightPoints\Bundle\GuzzleWsseBundle;

use EightPoints\Bundle\GuzzleWsseBundle\DependencyInjection\GuzzleWsseExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\ExpressionLanguage\Expression;

class GuzzleWsseBundle extends Bundle
{
    /**
     * @return  GuzzleWsseExtension
     * @throws  \LogicException
     */
    public function getContainerExtension() : GuzzleWsseExtension
    {
        if ($this->extension === null) {
            $extension = new GuzzleWsseExtension();

            if (!$extension instanceof ExtensionInterface) {
                $message = sprintf('%s is not a instance of ExtensionInterface', get_class($extension));

                throw new \LogicException($message);
            }

            $this->extension = $extension;
        }

        return $this->extension;
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @param string $clientName
     * @param Definition $handler
     */
    public function load(array $config, ContainerBuilder $container, string $clientName, Definition $handler)
    {
        if ($config['username'] && $config['password']) {
            $username = $config['username'];
            $password = $config['password'];
            $createdAtExpression = null;

            if (isset($config['created_at']) && $config['created_at']) {
                $createdAtExpression = $config['created_at'];
            }

            $wsse = new Definition('%guzzle_bundle.middleware.wsse.class%');
            $wsse->setArguments([$username, $password]);

            if ($createdAtExpression) {
                $wsse->addMethodCall('setCreatedAtTimeExpression', [$createdAtExpression]);
            }

            $wsseServiceName = sprintf('guzzle_bundle.middleware.wsse.%s', $clientName);

            $container->setDefinition($wsseServiceName, $wsse);

            $wsseExpression = new Expression(sprintf('service("%s").attach()', $wsseServiceName));

            $handler->addMethodCall('push', [$wsseExpression]);
        }
    }

    /**
     * @return ArrayNodeDefinition
     */
    public function getConfiguration() : ArrayNodeDefinition
    {
        $node = (new ArrayNodeDefinition($this->getPluginName()))
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('username')->defaultFalse()->end()
                    ->scalarNode('password')->defaultValue('')->end()
                    ->scalarNode('created_at')->defaultFalse()->end()
                ->end();

        return $node;
    }

    /**
     * @return string
     */
    public function getPluginName() : string
    {
        return 'wsse';
    }
}
