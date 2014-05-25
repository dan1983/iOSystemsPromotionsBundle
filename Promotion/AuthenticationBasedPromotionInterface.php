<?php

/*
 * This file is part of the iosystems-promotions package.
 *
 * (c) Marco Polichetti <sviluppatore@iosystems.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iOSystems\PromotionsBundle\Promotion;

/**
 * Represents the contract for role based promotions.
 */
interface AuthenticationBasedPromotionInterface
{
    /**
     * @return bool
     */
    public function requiresAuthentication();
}
