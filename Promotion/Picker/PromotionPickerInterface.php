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

use iOSystems\PromotionsBundle\Promotion\PromotionInterface;

interface PromotionPickerInterface
{
    /**
     * @param \iOSystems\PromotionsBundle\Promotion\PromotionInterface[] $promotions
     * @return mixed
     */
    public function pick(array $promotions);

    /**
     * @param PromotionInterface $promotion
     * @return bool
     */
    public function supports(PromotionInterface $promotion);
}
