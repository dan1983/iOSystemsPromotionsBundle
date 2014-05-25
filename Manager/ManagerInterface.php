<?php

/*
 * This file is part of the iosystems-promotions package.
 *
 * (c) Marco Polichetti <sviluppatore@iosystems.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iOSystems\PromotionsBundle\Manager;

use iOSystems\PromotionsBundle\Promotion\Loader\PromotionLoaderInterface;
use iOSystems\PromotionsBundle\PromotionableInterface;
use iOSystems\PromotionsBundle\Promotion\Picker\PromotionPickerInterface;
use iOSystems\PromotionsBundle\Voting\DecisionMaker\DecisionMakerInterface;
use iOSystems\PromotionsBundle\Voting\VoterInterface;

interface ManagerInterface
{
    /**
     * @param DecisionMakerInterface $voteDecider
     */
    public function setDecisionMaker(DecisionMakerInterface $voteDecider);

    /**
     * @return DecisionMakerInterface
     */
    public function getDecisionMaker();

    /**
     * @param PromotionPickerInterface $picker
     * @param string $alias
     */
    public function addPicker(PromotionPickerInterface $picker, $alias);

    /**
     * @return PromotionPickerInterface[]
     */
    public function getPickers();

    /**
     * @param PromotionLoaderInterface $loader
     */
    public function addLoader(PromotionLoaderInterface $loader);

    /**
     * @return PromotionLoaderInterface[]
     */
    public function getLoaders();

    /**
     * @param PromotionableInterface $promotionable
     * @return null|\iOSystems\PromotionsBundle\Promotion\PromotionInterface
     */
    public function getPromotion(PromotionableInterface $promotionable);

    /**
     * @param PromotionableInterface $promotionable
     * @return \iOSystems\PromotionsBundle\Promotion\PromotionInterface[]
     */
    public function getPromotions(PromotionableInterface $promotionable);

    /**
     * @param VoterInterface $voter
     */
    public function addVoter(VoterInterface $voter);

    /**
     * @return VoterInterface[]
     */
    public function getVoters();
}
