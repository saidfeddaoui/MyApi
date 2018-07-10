<?php

namespace App\DependencyInjection;

use App\EventSubscriber\ExceptionNormalizerEventSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExceptionNormalizerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $exceptionListenerDefinition = $container->findDefinition(ExceptionNormalizerEventSubscriber::class);
        $normalizers = $container->findTaggedServiceIds('app.normalizer');

        foreach ($normalizers as $id => $tags) {
            $exceptionListenerDefinition->addMethodCall('addNormalizer', [new Reference($id)]);
        }
    }

}