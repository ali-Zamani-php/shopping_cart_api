<?php

namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

class ItemFixtures extends Fixture
{
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function load(ObjectManager $manager): void
    {
        $csv_datei = "articles";
        $filesystem = new Filesystem();
        $projectDir = $this->projectDir;
        try {
            $file_names = scandir($projectDir . '/public/storage/articleimages/');
        } catch (\Exception $ex) {
            // Log the error or handle the exception
            //$logger->error(__CLASS__ . ':' . __LINE__ . '-' . $ex->getMessage());
            throw new FileNotFoundException('Directory not found: ' . $projectDir . '/public/storage/articleimages/');
        }

        try {
            $csv_input = fopen($projectDir . "/src/DataFixtures/DevelopmentData/" . $csv_datei . '.csv', "r");
        } catch (\Exception $ex) {
            // Log the error or handle the exception
            //$logger->error(__CLASS__ . ':' . __LINE__ . '-' . $ex->getMessage());
            throw new FileNotFoundException('File not found: ' . $projectDir . "/DevelopmentData/" . $csv_datei . '.csv');
        }
        $firstline = true;
        while (($data = fgetcsv($csv_input, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $foto_name = '';
                foreach ($file_names as $name) {
                    if ($name == $data[0] . '.jpg' || $name == $data[0] . '.png') {
                        $foto_name = $name;
                    }
                }
                $item = new Item();
                $item->setName($data['1']);
                $item->setPrice(intval($data['2']));
                $item->Setdescription($data['3']);
                $item->setImagePath('storage/articleimages/' . $foto_name);
                $manager->persist($item);
            }
            $firstline = false;
        }
        fclose($csv_input);
        $manager->flush();
    }
}

