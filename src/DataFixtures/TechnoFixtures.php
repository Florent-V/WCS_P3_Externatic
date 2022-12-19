<?php

namespace App\DataFixtures;

use App\Entity\Techno;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
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
            $techno->setName($langage['name']);
            $file = __DIR__ . '/data/logo/' . 'Logo' . $langage['name'] . '.png';
            if (
                copy($file, $this->containerBag->get('upload_directory') .
                'images/logo/' . 'Logo' . $langage['name'] . '.png')
            ) {
                $techno->setPicture('Logo' . $langage['name'] . '.png');
            }
            $manager->persist($techno);
        }
        $manager->flush();
    }
}
