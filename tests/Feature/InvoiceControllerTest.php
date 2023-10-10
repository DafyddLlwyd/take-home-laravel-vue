<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class InvoiceControllerTest extends TestCase
{
    public function testShowInvoices()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->followingRedirects()->get('/api/public/invoices');

        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'order_id',
                    'amount',
                    'status',
                    'created_at',
                    'updated_at',
                    'order' => [
                        'id',
                        'user_id',
                        'total_price',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links' => [
                '*' => [
                    'url',
                    'label',
                    'active',
                ],
            ],
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }

    public function testGenerateInvoice()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->followingRedirects()->get('/api/invoice/1');
        $response->assertStatus(200);
    }

    public function testDownloadInvoice()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->followingRedirects()->get('/api/invoice/1/download');

        $response->assertStatus(200);
    }
}
