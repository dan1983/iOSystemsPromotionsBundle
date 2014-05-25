<?php

/*
 * This file is part of the iosystems-promotions package.
 *
 * (c) Marco Polichetti <sviluppatore@iosystems.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iOSystems\PromotionsBundle;

use iOSystems\PromotionsBundle\DependencyInjection\Compiler\ManagerAwarePass;
use iOSystems\PromotionsBundle\DependencyInjection\Compiler\RegisterLoadersPass;
use iOSystems\PromotionsBundle\DependencyInjection\Compiler\RegisterPromotionPickersPass;
use iOSystems\PromotionsBundle\DependencyInjection\Compiler\RegisterVotersPass;
use iOSystems\PromotionsBundle\DependencyInjection\iOSystemsPromotionsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class iOSystemsPromotionsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterLoadersPass());
        $container->addCompilerPass(new RegisterVotersPass());
        $container->addCompilerPass(new RegisterPromotionPickersPass());
        $container->addCompilerPass(new ManagerAwarePass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new iOSystemsPromotionsExtension();
    }
}
