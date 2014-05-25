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

use iOSystems\PromotionsBundle\Promotion\ExpressionBasedPromotionInterface;
use iOSystems\PromotionsBundle\Promotion\PromotionInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

class ExpressionBasedVoter implements VoterInterface
{
    /**
     * @var ExpressionLanguage
     */
    private $language;

    /**
     * @param ExpressionLanguage $language
     */
    public function __construct(ExpressionLanguage $language)
    {
        $this->language = $language;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(PromotionInterface $promotion, $promotionable)
    {
        /** @var ExpressionBasedPromotionInterface $promotion */
        if (null === $promotion->getExpression()) {
            return null;
        }

        try {
            return $this->language->evaluate($promotion->getExpression());
        } catch(SyntaxError $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(PromotionInterface $promotion)
    {
        return $promotion instanceof ExpressionBasedPromotionInterface;
    }
}
