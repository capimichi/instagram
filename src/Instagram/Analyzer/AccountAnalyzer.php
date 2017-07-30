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

    /**
     * @return float|int
     */
    public function getRatioFollowersLikesComments()
    {
        $lastMedias = $this->account->getMedias(20);
        $averageLikesComments = 0;
        foreach ($lastMedias as $lastMedia) {
            /**
             * @var Media $lastMedia
             */
            $averageLikesComments += $lastMedia->getCommentsCount() + $lastMedia->getLikesCount();
        }
        if (!$averageLikesComments) {
            $averageLikesComments = 0.1;
        }
        $dividend = count($lastMedias) ? count($lastMedias) : 0.1;
        $averageLikesComments /= $dividend;
        $ratio = ($this->account->getFollowersCount() / $averageLikesComments) * 100;
        return $ratio;
    }

    /**
     * @return mixed
     */
    public function getRatioFollowersFollowing()
    {
        return min(1, $this->account->getFollowersCount() / $this->account->getFollowedCount());
    }

    /**
     * @return bool
     */
    public function isBot()
    {
        if ($this->account->getFollowersCount() < 1000) {
            return $this->getRatioFollowersLikesComments() < 7.8;
        }
        if ($this->account->getFollowersCount() < 10000) {
            return $this->getRatioFollowersLikesComments() < 3.8;
        }
        if ($this->account->getFollowersCount() < 100000) {
            return $this->getRatioFollowersLikesComments() < 2.3;
        }
        if ($this->account->getFollowersCount() < 1000000) {
            return $this->getRatioFollowersLikesComments() < 1.78;
        }
        return $this->getRatioFollowersLikesComments() < 1.66;
    }

}