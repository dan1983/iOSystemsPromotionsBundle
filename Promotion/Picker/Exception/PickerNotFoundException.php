<?php

/*
 * This file is part of the iosystems-promotions package.
 *
 * (c) Marco Polichetti <sviluppatore@iosystems.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iOSystems\PromotionsBundle\Promotion\Picker\Exception;

class PickerNotFoundException extends PickerException
{
    public function __construct($promotion, array $pickers)
    {
        parent::__construct(
            sprintf(
                'There is no picker to handle the promotion of type "%s" (available: %s).',
                is_object($promotion) ? get_class($promotion) : gettype($promotion),
                !$pickers ? 'none' : '"'.implode('", "', $pickers).'"'
            )
        );
    }
}
