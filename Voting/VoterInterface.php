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
use iOSystems\PromotionsBundle\PromotionableInterface;

interface VoterInterface
{
    /**
     * @param PromotionInterface $promotion
     * @param string|int|float|PromotionableInterface $promotionable
     * @return bool
     */
    public function vote(PromotionInterface $promotion, $promotionable);

    /**
     * @param PromotionInterface $promotion
     * @return bool
     */
    public function supports(PromotionInterface $promotion);
}
