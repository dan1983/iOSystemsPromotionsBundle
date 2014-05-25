<?php

/*
 * This file is part of the iosystems-promotions package.
 *
 * (c) Marco Polichetti <sviluppatore@iosystems.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iOSystems\PromotionsBundle\Voting\DecisionMaker;

use iOSystems\PromotionsBundle\PromotionableInterface;
use iOSystems\PromotionsBundle\Promotion\PromotionInterface;

interface DecisionMakerInterface
{
    /**
     * @param PromotionInterface $promotion
     * @param PromotionableInterface $promotionable
     * @return bool
     */
    public function makeDecision(PromotionInterface $promotion, PromotionableInterface $promotionable);
}
