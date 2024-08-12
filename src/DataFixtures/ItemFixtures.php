<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\Routing\RequestContext;


class ItemFixtures extends Fixture
{
    private $projectDir;
    private $requestContext;
    private $appUrl;

    public function __construct(RequestContext $requestContext,string $projectDir, string $appUrl)
    {
        $this->requestContext = $requestContext;
        $this->projectDir = $projectDir;
        $this->appUrl = $appUrl;
    }

    public function load(ObjectManager $manager): void
    {
        $shoppingCart = new ShoppingCart();
        $manager->persist($shoppingCart);
        $baseUrl = $this->appUrl; //$this->requestContext->getScheme() . '://' . $this->requestContext->getHost().':8000';
        $csv_datei = "articles";
        $projectDir = $this->projectDir;

        try {
            $file_names = scandir($projectDir . '/public/storage/articleimages/');
        } catch (\Exception $ex) {
            throw new FileNotFoundException('Directory not found: ' . $projectDir . '/public/storage/articleimages/');
        }

        try {
            $csv_input = fopen($projectDir . "/src/DataFixtures/DevelopmentData/" . $csv_datei . '.csv', "r");
        } catch (\Exception $ex) {
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
                $item->setImagePath($baseUrl.'/storage/articleimages/' . $foto_name);
                $manager->persist($item);
                $shoppingCartItem = new shoppingCartItem();
                $shoppingCartItem->setItem($item);
                $shoppingCartItem->setShoppingCart($shoppingCart);
                $shoppingCartItem->setQuantity(1);
                $manager->persist($shoppingCartItem);
            }
            $firstline = false;
        }
        fclose($csv_input);

        $manager->flush();
    }
}

