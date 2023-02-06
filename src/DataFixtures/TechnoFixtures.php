<?php

namespace App\DataFixtures;

use App\Entity\Techno;
use App\EventListener\TechnoListener;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class TechnoFixtures extends Fixture
{
    public static int $technoIndex = 0;

    public function __construct(
        private readonly ContainerBagInterface $containerBag,
        private readonly DecoderInterface $decoder
    ) {
    }


    public function load(ObjectManager $manager): void
    {
        $file = 'langage.csv';
        $filePath = __DIR__ . '/data/' . $file;
        $context = [
            CsvEncoder::DELIMITER_KEY => ';',
            CsvEncoder::ENCLOSURE_KEY => '"',
            CsvEncoder::ESCAPE_CHAR_KEY => '\\',
            CsvEncoder::KEY_SEPARATOR_KEY => ';',
        ];
        $csv = $this->decoder->decode(file_get_contents($filePath), 'csv', $context);
        foreach ($csv as $langage) {
            self::$technoIndex++;
            $techno = new Techno();
            $techno->setIsFixture(true);
            $techno->setName($langage['name']);
            $file = __DIR__ . '/data/logo/' . 'Logo' . $langage['name'] . '.png';
            if (
                copy($file, $this->containerBag->get('upload_directory') .
                'images/logo/' . 'Logo' . self::$technoIndex . '.png')
            ) {
                $techno->setPicture('Logo' . self::$technoIndex . '.png');
            }
            $this->addReference('techno_' . self::$technoIndex, $techno);
            $manager->persist($techno);
        }
        $manager->flush();
    }
}
