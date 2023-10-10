<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ShoppingCartControllerTest extends TestCase
{
    protected $user;

protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testSaveShoppingCart()
    {
        // add a product to cart
        $cartItems = [1, 2];

        $response = $this->actingAs($this->user)
            ->followingRedirects()
            ->post('/api/public/cart', ['items' => $cartItems]);

        $response->assertStatus(200);
    }

    public function testGetShoppingCart()
    {
        $cartItemIds = [1, 2];

        $this->actingAs($this->user)
            ->followingRedirects()
            ->post('/api/public/cart', ['userId' => $this->user->id, 'items' => $cartItemIds]);

        $response = $this->actingAs($this->user)
            ->get('/api/public/cart/' . $this->user->id);

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson(function (AssertableJson $json) {
                $json->each(function ($item) {
                    $item->has('id');
                    $item->has('name');
                    $item->has('description');
                    $item->has('price');
                    $item->has('category_id');
                    $item->has('photo');
                    $item->has('created_at');
                    $item->has('updated_at');
                    $item->has('deleted_at');
                });
            });


    }

    public function testSubmitOrder()
    {
        $cartItemIds = [1, 2];

        $this->actingAs($this->user)
            ->followingRedirects()
            ->post('/api/public/cart', ['userId' => $this->user->id, 'items' => $cartItemIds]);

        $cartItems = [
            [
                'id' => 1,
                'quantity' => 1,
            ],
            [
                'id' => 2,
                'quantity' => 3,
            ]
        ];

        $response = $this->actingAs($this->user)
            ->post('/api/public/cart/submit', [
                'userId' => $this->user->id,
                'items' => $cartItems,
                'total' => 100, // Example total price
            ]);

        $response->assertStatus(200);

        // assert order created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
        ]);
    }
}

