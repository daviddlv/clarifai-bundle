<?php

namespace ClarifaiBundle\DependencyInjection;

use ClarifaiBundle\Client\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ClarifaiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $authConfig = $config['auth'];
        unset($config['auth']);

        $apiClient = new Definition(Client::class, [
            $authConfig
        ]);

        $container->setDefinition('clarifai.api.client', $apiClient);
    }
}
