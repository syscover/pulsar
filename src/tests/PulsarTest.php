<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class PulsarTest
 *
 * phpunit tests/PulsarTest
 */

class PulsarTest extends TestCase
{

    public function testPackageIndex()
    {
        $response = $this->call('GET', route('package'));

        $this->assertEquals(200, $response->status());
    }

    public function testNewPackage()
    {
        $response = $this->call('GET', route('createPackage'));

        $this->assertEquals(200, $response->status());
    }
}
