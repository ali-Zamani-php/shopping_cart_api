<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartApiTest extends WebTestCase
{
    const HTTP_HOST = 'localhost:8000/api/v1';

    public function testCreateShoppingCart(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);
        $client->request('POST', '/shopping-cart');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('shoppingCartItems', $responseData);
        $this->assertIsArray($responseData['shoppingCartItems']);
    }

    public function testShowShoppingCartSuccess(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);
        $shoppingCartId = 1;

        $client->request('GET', '/shopping-cart/' . $shoppingCartId);
        $request = $client->getRequest();
        $url = $request->getUri();
        echo "Called URL: " . $url . "\n";
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($shoppingCartId, $responseData['id']);
    }

    public function testCreateItemSuccess(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);


        $shoppingCartId = 1;
        $itemId = 1;

        $client->request('POST', "/shopping-cart/{$shoppingCartId}/item/{$itemId}");

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());


        $responseData = json_decode($response->getContent(), true);


        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('item', $responseData);
    }

    public function testCreateItemNotFoundForItem(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);


        $shoppingCartId = 1;
        $itemId = 9999; // Assumed to be non-existent

        $client->request('POST', "/shopping-cart/{$shoppingCartId}/item/{$itemId}");

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());


        $this->assertStringContainsString('No Item found for id ' . $itemId, $response->getContent());
    }

    public function testCreateItemNotFoundForCart(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);


        $shoppingCartId = 9999; // Assumed to be non-existent
        $itemId = 1;

        $client->request('POST', "/shopping-cart/{$shoppingCartId}/item/{$itemId}");

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());


        $this->assertStringContainsString('No Cart found for id ' . $shoppingCartId, $response->getContent());
    }

    public function testUpdateItemSuccess(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);
        $shoppingCartId = 1;
        $itemId = 1;
        $data = ['quantity' => 5];

        $client->request('PUT', "/shopping-cart/{$shoppingCartId}/item/{$itemId}", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());


        $responseData = json_decode($response->getContent(), true);


        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('quantity', $responseData);
        $this->assertEquals($data['quantity'], $responseData['quantity']);
    }

    public function testUpdateItemNotFoundForItem(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);
        $shoppingCartId = 1;
        $itemId = 9999;
        $data = ['quantity' => 5];

        $client->request('PUT', "/shopping-cart/{$shoppingCartId}/item/{$itemId}", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());


        $this->assertStringContainsString('No Item found for id ' . $itemId, $response->getContent());
    }

    public function testUpdateItemNotFoundForCart(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);
        $shoppingCartId = 9999; // Assumed to be non-existent
        $itemId = 1;
        $data = ['quantity' => 5];

        $client->request('PUT', "/shopping-cart/{$shoppingCartId}/item/{$itemId}", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());


        $this->assertStringContainsString('No Cart found for id ' . $shoppingCartId, $response->getContent());
    }

    public function testUpdateItemValidationError(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);
        $shoppingCartId = 1;
        $itemId = 1;
        $data = ['quantity' => -5]; // Invalid quantity

        $client->request('PUT', "/shopping-cart/{$shoppingCartId}/item/{$itemId}", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());


        $responseData = json_decode($response->getContent(), true);


        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertStringContainsString('This value should be positive.', $responseData['error']);
    }

    public function testRemoveItemSuccess(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);

        $shoppingCartId = 1;
        $itemId = 1;

        $client->request('DELETE', "/shopping-cart/{$shoppingCartId}/item/{$itemId}");

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testRemoveItemNotFoundForItem(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);

        $shoppingCartId = 1;
        $itemId = 9999; // Assumed to be non-existent

        $client->request('DELETE', "/shopping-cart/{$shoppingCartId}/item/{$itemId}");

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());


        $this->assertStringContainsString('No Item found for id ' . $itemId, $response->getContent());
    }

    public function testRemoveItemNotFoundForCart(): void
    {
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
        ]);

        $shoppingCartId = 9999; // Assumed to be non-existent
        $itemId = 1;

        $client->request('DELETE', "/shopping-cart/{$shoppingCartId}/item/{$itemId}");

        $response = $client->getResponse();


        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());


        $this->assertStringContainsString('No Cart found for id ' . $shoppingCartId, $response->getContent());
    }
}
