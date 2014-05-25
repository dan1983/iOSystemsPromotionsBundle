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

class RegisterVotersPass implements CompilerPassInterface
{

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!($container->hasAlias($alias = 'iosystems_promotions.manager'))) {
            return;
        }

        if (!$container->getDefinition($definition = (string) $container->getAlias($alias))) {
            return;
        }

        $managerDefinition = $container->getDefinition($definition);

        foreach ($container->findTaggedServiceIds('iosystems_promotions.voter') as $id => $attributes) {
            $class = trim($container->getDefinition($id)->getClass(), '%');
            $reflClass = new \ReflectionClass(class_exists($class) ? $class : $container->getParameter($class));

            $interface = 'iOSystems\PromotionsBundle\Voting\VoterInterface';
            if (!$reflClass->implementsInterface($interface)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
            }

            $managerDefinition->addMethodCall('addVoter', array(new Reference($id)));
        }
    }
}
