<?php

/*
 * This file is part of the iosystems-promotions package.
 *
 * (c) Marco Polichetti <sviluppatore@iosystems.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iOSystems\PromotionsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ManagerAwarePass implements CompilerPassInterface
{

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!($container->hasAlias($managerAlias = 'iosystems_promotions.manager'))) {
            return;
        }
        /** @var \Symfony\Component\DependencyInjection\Definition[] $managerAwareDefinitions */
        $managerAwareDefinitions = array();

        // Resolve the voting decision maker class
        $deciderDefinition = $container->getDefinition((string) $container->getAlias('iosystems_promotions.voting_decisor_maker'));
        $class = trim($deciderDefinition->getClass(), '%');
        $reflClass = new \ReflectionClass(class_exists($class) ? $class : $container->getParameter($class));

        // Check if decider implements ManagerAwareInterface
        if ($reflClass->implementsInterface('iOSystems\PromotionsBundle\Manager\ManagerAwareInterface')) {
            $managerAwareDefinitions[] = $deciderDefinition;
        }

        // Check if any voter implements ManagerAwareInterface
        foreach ($container->findTaggedServiceIds('iosystems_promotions.voter') as $id => $attributes) {
            $class = trim($container->getDefinition($id)->getClass(), '%');
            $reflClass = new \ReflectionClass(class_exists($class) ? $class : $container->getParameter($class));

            if ($reflClass->implementsInterface('iOSystems\PromotionsBundle\Manager\ManagerAwareInterface')) {
                $managerAwareDefinitions[] = $container->getDefinition($id);
            }
        }

        // Setting the manager depedency for implementations of ManagerAwareInterface
        foreach ($managerAwareDefinitions as $definition) {
            $definition->addMethodCall('setManager', array(new Reference($managerAlias)));
        }
    }
}
