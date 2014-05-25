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
use iOSystems\PromotionsBundle\Promotion\Picker\Exception\ConflictingPickersException;
use iOSystems\PromotionsBundle\Promotion\Picker\Exception\MoreThanOnePickerException;
use iOSystems\PromotionsBundle\Promotion\Picker\Exception\PickerNotFoundException;
use iOSystems\PromotionsBundle\Promotion\Picker\PromotionPickerInterface;
use iOSystems\PromotionsBundle\Promotion\PromotionInterface;
use iOSystems\PromotionsBundle\PromotionableInterface;
use iOSystems\PromotionsBundle\Voting\DecisionMaker\DecisionMakerInterface;
use iOSystems\PromotionsBundle\Voting\VoterInterface;

class DefaultManager implements ManagerInterface
{
    /**
     * @var DecisionMakerInterface
     */
    private $voteDecider;

    /**
     * @var PromotionLoaderInterface[]
     */
    private $loaders = array();

    /**
     * @var PromotionPickerInterface[]
     */
    private $promotionPickers;

    /**
     * @var VoterInterface[]
     */
    private $voters = array();

    /**
     * {@inheritdoc}
     */
    public function setDecisionMaker(DecisionMakerInterface $voteDecider)
    {
        $this->voteDecider = $voteDecider;
    }

    /**
     * {@inheritdoc}
     */
    public function getDecisionMaker()
    {
        return $this->voteDecider;
    }

    /**
     * {@inheritdoc}
     */
    public function addPicker(PromotionPickerInterface $picker, $alias)
    {
        $this->promotionPickers[$alias] = $picker;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPickers()
    {
        return $this->promotionPickers;
    }

    /**
     * {@inheritdoc}
     */
    public function addLoader(PromotionLoaderInterface $loader)
    {
        $this->loaders[get_class($loader)] = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoaders()
    {
        return $this->loaders;
    }

    /**
     * @param PromotionInterface $promotion
     * @return PromotionPickerInterface[]
     */
    private function getSupportedPickers(PromotionInterface $promotion)
    {
        if (empty($this->promotionPickers)) {
            return array();
        }

        return array_filter(
            $this->promotionPickers,
            function (PromotionPickerInterface $picker) use ($promotion) {
                return $picker->supports($promotion);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getPromotion(PromotionableInterface $promotionable)
    {
        /** @var PromotionPickerInterface[] $pickers */
        $pickers = array();

        $promotions = $this->getPromotions($promotionable);
        foreach ($promotions as $promotion) {
            $supportedPickers = $this->getSupportedPickers($promotion);

            // No picker can handle this promotion
            if (empty($supportedPickers)) {
                throw new PickerNotFoundException(
                    $promotion,
                    $this->promotionPickers ? array_keys($this->promotionPickers) : array()
                );
            }

            // More than one picker can handle this promotion
            if (count($supportedPickers) > 1) {
                throw new MoreThanOnePickerException(
                    $promotion,
                    $this->promotionPickers ? array_keys($this->promotionPickers) : array()
                );
            }

            // Push the supported picker to further check consistency
            $picker = array_pop($supportedPickers);
            if (!in_array($picker, $pickers)) {
                $pickers[] = $picker;
            }
        }

        // More than one picker to handle promotions
        if (count($pickers) > 1) {
            throw new ConflictingPickersException(array_keys($this->promotionPickers));
        }

        if (!empty($pickers)) {
            $picker = array_pop($pickers);

            return $picker->pick($promotions);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPromotions(PromotionableInterface $promotionable)
    {
        // Loading and merging promotions
        $promotions = array();
        foreach ($this->loaders as $loader) {
            $promotions = array_merge($promotions, $loader->load());
        }

        // Return promotions based on decider decision
        $decider = $this->voteDecider;

        return array_filter(
            $promotions,
            function (PromotionInterface $promotion) use ($decider, $promotionable) {
                return $decider->makeDecision($promotion, $promotionable);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function addVoter(VoterInterface $voter)
    {
        $this->voters[get_class($voter)] = $voter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVoters()
    {
        return $this->voters;
    }
}
