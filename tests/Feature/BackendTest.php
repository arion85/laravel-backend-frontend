<?php

namespace Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BackendTest extends TestCase
{
    protected string $side;
    protected function setUp(): void
    {
        error_reporting(E_ALL);
        $this->side = '--side=be';

        parent::setUp();
    }
    public function test_backend(): void
    {
        $response = $this->get('http://adm.lbf.loc');

        $response->assertStatus(200);
    }
}
