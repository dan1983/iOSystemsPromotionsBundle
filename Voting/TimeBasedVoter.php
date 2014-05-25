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
use iOSystems\PromotionsBundle\Promotion\TimeBasedPromotionInterface;

class TimeBasedVoter implements VoterInterface
{
    /**
     * {@inheritdoc}
     */
    public function vote(PromotionInterface $promotion, $promotionable)
    {
        /** @var TimeBasedPromotionInterface $promotion */
        $start = $promotion->getValidFrom();
        $end   = $promotion->getValidUntil();

        if (null === $start && null === $end) {
            return null;
        }

        $start = $start instanceof \DateTime ? $start->getTimestamp() : $start;
        $end = $end instanceof \DateTime ? $end->getTimestamp() : $end;

        $now = time();
        if (null === $start) {
            return $now <= $end;
        }

        if (null === $end) {
            return $now >= $start;
        }

        return $now >= $start && $now <= $end;
    }

    /**
     * @param PromotionInterface $promotion
     * @return bool
     */
    public function supports(PromotionInterface $promotion)
    {
        return $promotion instanceof TimeBasedPromotionInterface;
    }
}
