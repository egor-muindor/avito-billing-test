<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест ответа при регистрации новой сессии оплаты
     *
     * @return void
     */
    public function testRegisterValidData(): void
    {
        $data = [
            'target' => 'Some target payment',
            'amount' => 12345.1231,
            'callback_url' => 'http://test.com/callback.php'
        ];
        $response = $this->post(route('api.payments.register'), $data);

        $response->assertStatus(201)->assertJsonStructure([
            'sessionUrl'
        ]);
    }

    public function testRegisterInvalidData(): void
    {
        $data = [
            'amount' => 'some string',
            'target' => 123123
        ];
        $response = $this->postJson(route('api.payments.register'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'amount', 'target'
            ]);
    }
}
