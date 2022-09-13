<?php

namespace App\DataFixtures;

use App\Entity\Design;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DesignFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $design = new Design();
        $design->setTitle('A very nice title!');
        $design->setLogo('test');
        $design->setBackgroundImage('test2');
        $design->setPageColor('#000000');
        $design->setTextColor('#FFFFFF');
        $design->setSecondaryTextColor('#E50C12');
        $design->setPhoneNumber('861564564');
        $design->setCompanyName('Test company');
        $design->setAddress('Test address');
        $design->setCountry('Aruba');
        $design->setPostalCode('AB-15487');
        $design->setCopyright('Copyright © 2022 TEST. All Rights Reserved.');

        $manager->persist($design);
        $manager->flush();
    }
}