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

use iOSystems\PromotionsBundle\Promotion\AuthenticationBasedPromotionInterface;
use iOSystems\PromotionsBundle\Promotion\PromotionInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AuthenticationBasedVoter implements VoterInterface
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
        /** @var AuthenticationBasedPromotionInterface $promotion */
        if (!$promotion->requiresAuthentication() ) {
            return true;
        }

        return $this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED');
    }

    /**
     * @param PromotionInterface $promotion
     * @return bool
     */
    public function supports(PromotionInterface $promotion)
    {
        return $promotion instanceof AuthenticationBasedPromotionInterface;
    }
}
