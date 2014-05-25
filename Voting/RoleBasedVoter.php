<?php

/*
 * This file is part of the iosystems-promotions package.
 *
 * (c) Marco Polichetti <sviluppatore@iosystems.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iOSystems\PromotionsBundle\Voting;

use iOSystems\PromotionsBundle\Promotion\PromotionInterface;
use iOSystems\PromotionsBundle\Promotion\RoleBasedPromotionInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class RoleBasedVoter implements VoterInterface
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(PromotionInterface $promotion, $promotionable)
    {
        /** @var RoleBasedPromotionInterface $promotion */
        if (!count($roles = $promotion->getRoles())) {
            return null;
        }

        foreach ($roles as $role) {
            $role = ($role instanceof RoleInterface) ? $role->getRole() : $role;

            if (!$promotion->requiresAllRoles() && $this->securityContext->isGranted($role)) {
                return true;
            }

            if (!($this->securityContext->isGranted($role) )) {
                return false;
            }
        }

        return $promotion->requiresAllRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function supports(PromotionInterface $promotion)
    {
        return $promotion instanceof RoleBasedPromotionInterface;
    }
}
