<?php
// AiCoreDomainExtension.php
/**
 * User: aivanov
 * Date: 19.12.16
 * Time: 19:35
 */
namespace Ai\CoreDomainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class AiCoreDomainExtension extends ConfigurableExtension
{
    /**
     * @inheritDoc
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator([
                __DIR__.'/../Resources/config/',
            ])
        );
        $loader->load('repositories.xml');

        $loader->load('security_guards.xml');

        $definition = $container->getDefinition('ai_core_domain.session_token_authenticator')
                                ->addArgument($config['session_token_authenticator']['token_source']);

        switch ($config['session_token_authenticator']['token_source']) {
            case 'header':
                $definition->addArgument($config['session_token_authenticator']['header_name']);
                break;
            case 'query':
                $definition->addArgument($config['session_token_authenticator']['query_param_name']);
                break;
            default:
                throw new \InvalidArgumentException('Wrong token source provided');
        }
    }
}