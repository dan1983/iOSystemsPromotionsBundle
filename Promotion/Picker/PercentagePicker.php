<?php

/*
 * This file is part of the iosystems-promotions package.
 *
 * (c) Marco Polichetti <sviluppatore@iosystems.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iOSystems\PromotionsBundle\Promotion\Picker;

use iOSystems\PromotionsBundle\Promotion\PercentagePromotionInterface;
use iOSystems\PromotionsBundle\Promotion\PromotionInterface;

class PercentagePicker implements PromotionPickerInterface
{
    /**
     * {@inheritdoc}
     */
    public function pick(array $promotions)
    {
        $discounts = array_map(
            function (PercentagePromotionInterface $promotion) {
                return $promotion->getPercentage();
            },
            $promotions
        );

        if (empty($discounts)) {
            return null;
        }

        // Sort high to low
        arsort($discounts);

        return $promotions[key($discounts)];
    }

    /**
     * {@inheritdoc}
     */
    public function supports(PromotionInterface $promotion)
    {
        return $promotion instanceof PercentagePromotionInterface;
    }
}
