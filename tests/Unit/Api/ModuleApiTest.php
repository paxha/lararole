<?php

namespace Lararole\Tests\Unit\Api;

use Lararole\Tests\TestCase;

class ModuleApiTest extends TestCase
{
    public function testModules()
    {
        $this->artisan('migrate:modules');

        $response = $this->get('/lararole/api/modules');

        $response->assertStatus(200)->assertJsonStructure([
            'key', 'id', 'name', 'alias', 'icon', 'created_at', 'updated_at'
        ], $response->json('modules')[0])->assertJsonStructure([
            'key', 'id', 'name', 'alias', 'icon', 'created_at', 'updated_at', 'children'
        ], $response->json('modules')[1])->assertJsonStructure([
            'key', 'id', 'name', 'alias', 'icon', 'created_at', 'updated_at'
        ], $response->json('modules')[1]['children'][0]);
    }
}
