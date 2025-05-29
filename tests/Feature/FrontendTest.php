<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    protected string $side;
    protected function setUp(): void
    {
        error_reporting(E_ALL);
        $this->side = '--side=fe';

        parent::setUp();
    }

    public function test_frontend(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
