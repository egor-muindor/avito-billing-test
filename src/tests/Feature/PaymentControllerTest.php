<?php

namespace Tests\Feature;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCorrectUUIDForm(): void
    {
        $session = factory(Payment::class)->create();

        $response = $this->get(route('payments.card.show')."?sessionId={$session->id}");
        $response->assertStatus(200);
    }

    public function testIncorrectUUIDForm(): void
    {
        $session = 'random string';

        $response = $this->get(route('payments.card.show')."?sessionId=$session");
        $response->assertStatus(404);
    }
}
