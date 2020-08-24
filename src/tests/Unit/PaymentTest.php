<?php

namespace Tests\Unit;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function testScopeTo(): void
    {
        factory(Payment::class)->create();
        factory(Payment::class)->create(['created_at' => now()->addMinute()]);
        self::assertSame(Payment::to(now())->get()->count(), 1);
        self::assertSame(Payment::to(now()->addMinutes(2))->get()->count(), 2);
        self::assertSame(Payment::to(now()->subMinute())->get()->count(), 0);
    }

    public function testScopeFrom(): void
    {
        factory(Payment::class,3)->create();
        self::assertSame(Payment::from(now()->addMinute())->get()->count(), 0);
        factory(Payment::class,5)->create(['created_at' => now()->addMinute()]);
        factory(Payment::class,5)->create(['created_at' => now()->subMinute()]);
        self::assertSame(Payment::from(now())->get()->count(), 8);
    }

    public function testScopeNotExpired(): void
    {
        $start = Payment::notExpired()->count();
        factory(Payment::class, 4)->create();
        self::assertSame(Payment::notExpired()->get()->count(), 4);
        factory(Payment::class, 2)->create(['expires_at' => now()->subMinute()]);
        self::assertSame(Payment::notExpired()->get()->count(),  4);

    }

    public function testScopeExpired(): void
    {
        factory(Payment::class, 4)->create();
        self::assertSame(Payment::expired()->get()->count(), 0);
        factory(Payment::class, 2)->create(['expires_at' => now()->subMinute()]);
        self::assertSame(Payment::expired()->get()->count(), 2);
    }

    public function testIsNotExpired(): void
    {
        $payment = factory(Payment::class)->create();
        $payment = Payment::find($payment->id);
        self::assertTrue($payment->isNotExpired());
        $payment = factory(Payment::class)->create(['expires_at' => now()->subMinute()]);
        $payment = Payment::find($payment->id);
        self::assertFalse($payment->isNotExpired());
    }

    public function testIsExpired(): void
    {
        $payment = factory(Payment::class)->create();
        $payment = Payment::find($payment->id);
        self::assertFalse($payment->isExpired());
        $payment = factory(Payment::class)->create(['expires_at' => now()->subMinute()]);
        $payment = Payment::find($payment->id);
        self::assertTrue($payment->isExpired());
    }
}
