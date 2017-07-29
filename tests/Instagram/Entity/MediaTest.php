<?php

use Capimichi\Instagram\Entity\Media;
use Capimichi\Instagram\InstagramSession;
use Capimichi\Instagram\Entity\Account;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{


    public function testCanParseData()
    {
        $media = Media::createFromCode("BUWlAxehCrF");
        self::assertInternalType("string", $media->getId());
//        self::assertInternalType("stdClass", $media->getDimensions());
        self::assertInternalType("string", $media->getUrl());
        self::assertInternalType("bool", $media->isVideo());
        self::assertInternalType("bool", $media->isCaptionEdited());
        self::assertInternalType("bool", $media->isCommentsDisabled());
        self::assertInternalType("array", $media->getTaggedUsers());
        self::assertInternalType("string", $media->getCaption());
        self::assertInternalType("int", $media->getTakenTimestamp());
        self::assertNotEmpty($media->getOwner());

    }


}