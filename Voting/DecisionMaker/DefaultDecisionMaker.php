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

use iOSystems\PromotionsBundle\Manager\ManagerAwareInterface;
use iOSystems\PromotionsBundle\Manager\ManagerInterface;
use iOSystems\PromotionsBundle\Promotion\PromotionInterface;
use iOSystems\PromotionsBundle\PromotionableInterface;
use iOSystems\PromotionsBundle\Voting\VoterInterface;

class DefaultDecisionMaker implements DecisionMakerInterface, ManagerAwareInterface
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * {@inheritdoc}
     */
    public function makeDecision(PromotionInterface $promotion, PromotionableInterface $promotionable)
    {
        // Get supported voters and store each voter vote
        $voters = $this->getSupportedVoters($promotion, $promotionable);
        $votes = array_map(
            function (VoterInterface $voter) use ($promotion, $promotionable) {
                return $voter->vote($promotion, $promotionable);
            },
            $voters
        );

        // Positive outcome if empty or votes are null
        if (count($votes) === count(array_filter($votes, 'is_null'))) {
            return true;
        }

        // Make the final decision
        foreach ($votes as $vote) {
            // Logical AND: stop at first negative vote
            if ($promotion->requiresAllVoters() && (false === $vote)) {
                return false;
            }

            // Logical OR: stop at first positive vote
            if (!$promotion->requiresAllVoters() && (true === $vote)) {
                return true;
            }
        }

        return $promotion->requiresAllVoters();
    }

    /**
     * {@inheritdoc}
     */
    public function setManager(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param PromotionInterface $promotion
     * @return VoterInterface[]
     */
    private function getSupportedVoters(PromotionInterface $promotion)
    {
        return array_filter(
            $this->manager->getVoters(),
            function (VoterInterface $voter) use ($promotion) {
                return $voter->supports($promotion);
            }
        );
    }
}
