<?php

namespace Capimichi\Instagram\Analyzer;

use Capimichi\Instagram\Entity\Account;
use Capimichi\Instagram\Entity\Media;

class AccountAnalyzer
{
    /**
     * @var Account
     */
    protected $account;

    /**
     * AccountAnalyzer constructor.
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return float|int
     */
    public function getFollowersMediasCountAverage()
    {
        $followers = $this->account->getFollowers();
        $sum = 0;
        foreach ($followers as $follower) {
            $sum += $follower->getMediasCount();
        }
        return $sum / count($followers);
    }

    /**
     * @return float|int
     */
    public function getFollowersFollowersCountAverage()
    {
        $followers = $this->account->getFollowers();
        $sum = 0;
        foreach ($followers as $follower) {
            $sum += $follower->getFollowersCount();
        }
        return $sum / count($followers);
    }

    /**
     * @return float|int
     */
    public function getFollowersFollowedCountAverage()
    {
        $followers = $this->account->getFollowers();
        $sum = 0;
        foreach ($followers as $follower) {
            $sum += $follower->getFollowedCount();
        }
        return $sum / count($followers);
    }

}