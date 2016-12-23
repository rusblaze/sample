<?php
// Configuration.php
/**
 * User: aivanov
 * Date: 19.12.16
 * Time: 19:49
 */
namespace Ai\CoreDomainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\{
    Builder\TreeBuilder,
    ConfigurationInterface
};

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ai_core_domain');

        $rootNode
            ->children()
                ->arrayNode('session_token_authenticator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('token_source')
                            ->values(['header', 'query'])
                            ->defaultValue('header')
                            ->isRequired()
                        ->end()
                        ->scalarNode('header_name')->defaultValue('X-SESSION-TOKEN')->end()
                        ->scalarNode('query_param_name')->defaultValue('ssid')->end()
                    ->end()
                ->end() // session_token_authenticator
            ->end();

        return $treeBuilder;
    }
}