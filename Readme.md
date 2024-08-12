# ShoppingCart API

## Overview

The ShoppingCart API is a RESTful service that allows clients to manage shopping carts and items. It provides endpoints to create a shopping cart, add items to the cart, remove items, update item quantities, and retrieve the cart details.

## Features

- **Create a Shopping Cart**: Start a new shopping cart session.
- **Add Items to Cart**: Add one or more items to a shopping cart.
- **Remove Items from Cart**: Remove items from a shopping cart.
- **Update Item Quantities**: Adjust the quantity of items in a cart.
- **View Shopping Cart**: Retrieve all items in a shopping cart.

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/ali-Zamani-php/shopping_cart_api.git
   cd shoppingcart-api

2. **Install the required dependencies using Composer.:**

   ```bash
   composer install

3. **Set Up Environment Variables**

   ```bash
   cp .env.example .env
   cp .env.test.example .env.test

4. **Set Up and seed DB**

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:fixtures:load

5. **Run Tests**

   ```bash
   php bin/console --env=test doctrine:database:create
   php bin/console --env=test doctrine:schema:create
   php bin/console --env=test doctrine:fixtures:load
   php bin/phpunit  

## API Endpoints

### 1. Create a Shopping Cart

- **Endpoint:** `POST /shopping-cart`
- **Description:** Creates a new shopping cart.
- **Response:**
   - **201 Created**
   - Example Response:
     ```json
     
       {
          "id": 6,
          "shoppingCartItems": []
       }
     
     ```

### 2. Add an Item to the Shopping Cart

- **Endpoint:** `POST /shopping-cart/{cart_id}/item/{item_id}`
- **Description:** Adds an item to the shopping cart.
- **Path Parameters:**
   - `cart_id`: The ID of the shopping cart.
   - `item_id`: The ID of the item.

- **Response:**
   - **201 Created**
   - Example Response:
     ```json
         {
           "id": 187,
           "item": {
                 "id": 211,
                 "name": "DeLorean DMC-12",
                 "description": "Wagen aus dem Film \"Zurück in die Zukunft\" zu verkaufen",
                 "price": "30.00",
                 "image_path": "http://localhost:80/storage/articleimages/1.jpg"
        },
        "quantity": 2
     }
     ```

### 3. Remove an Item from the Shopping Cart

- **Endpoint:** `DELETE /shopping-cart/{cart_id}/item/{item_id}`
- **Description:** Removes an item from the shopping cart.
- **Path Parameters:**
   - `cart_id`: The ID of the shopping cart.
   - `item_id`: The ID of the item.
- **Response:**
   - **204 NO-Content**



### 4. Edit an Item in the Shopping Cart

- **Endpoint:** `PUT /shopping-cart/{cart_id}/item/{item_id}`
- **Description:** Edits the quantity of an item in the shopping cart.
- **Path Parameters:**
   - `cart_id`: The ID of the shopping cart.
   - `item_id`: The ID of the item.
- **Request Body:**
   - Example:
     ```json
     {
       "quantity": 3
     }
     ```
- **Response:**
   - **200 OK**
   - Example Response:
     ```json
     {
         "id": 196,
         "item": {
                "id": 220,
                "name": "Tachoreparatur",
                "description": "Hilfe für defekter Tacho Software /Hardware",
                "price": "200.00",
                "image_path": "http://localhost:80/storage/articleimages/10.jpg"
         },
         "quantity": 3
     }
     ```

### 5. View Shopping Cart

- **Endpoint:** `GET /shopping-cart/{cart_id}`
- **Description:** Retrieves the shopping cart with all its items.
- **Path Parameters:**
   - `cart_id`: The ID of the shopping cart.
- **Response:**
   - **200 OK**
   - Example Response:
     ```json
      {
         "id": 14,
         "shoppingCartItems": [
                                      {
                                         "id": 187,
                                         "item": {
                                                "id": 211,
                                                "name": "DeLorean DMC-12",
                                                "description": "Wagen aus dem Film \"Zurück in die Zukunft\" zu verkaufen",
                                                "price": "30.00",
                                                "image_path": "http://localhost:80/storage/articleimages/1.jpg"
                                                 },
                                           "quantity": 2
                                      },
                                      {
                                         "id": 188,
                                          "item": {
                                                  "id": 212,
                                                  "name": "Lotus Esprit S1",
                                                  "description": "Wagen aus dem Film \"007 - Der Spion, der mich liebte\"",
                                                  "price": "25.00",
                                                  "image_path": "http://localhost:80/storage/articleimages/2.jpg"
                                          },
                                          "quantity": 1
                                      }      
                        ]
     
        }
     ```

