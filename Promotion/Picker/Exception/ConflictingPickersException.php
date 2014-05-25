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

class ConflictingPickersException extends PickerException
{
    public function __construct(array $pickers)
    {
        parent::__construct(
            sprintf(
                'There is more than one picker ("%s") for handling matching promotions.',
                implode('", "', $pickers)
            )
        );
    }
}
