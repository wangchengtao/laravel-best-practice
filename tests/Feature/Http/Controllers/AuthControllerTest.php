<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Constants\BizCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin()
    {
        $params = [
            'name' => 'test',
            'password' => 'password',
        ];

        User::factory()->create($params);

        $this->assertDatabaseCount(User::class, 1);

        $this->postJson(route('auth.login'), [
            'name' => 'test',
            'password' => 'password',
        ])->assertJsonPath('code', BizCode::SUCCESS->value);

        // 用户名错误
        $this->postJson(route('auth.login'), [
            'name' => 'test1',
            'password' => 'password',
        ])->assertJsonPath('code', BizCode::FAIL->value);

        // 密码错误
        $this->postJson(route('auth.login'), [
            'name' => 'test',
            'password' => 'password1',
        ])->assertJsonPath('code', BizCode::FAIL->value);
    }

    public function testMe()
    {
        $params = [
            'name' => 'test',
            'password' => 'password',
        ];

        $user = User::factory()->create($params);

        $this->assertDatabaseCount(User::class, 1);

        $this->actingAs($user)
            ->get(route('auth.me'))
            ->assertJsonPath('data.name', 'test');
    }

    // actingAs 有报错
    public function testLogout()
    {
        $user = User::factory()->create();

        $this->assertDatabaseCount(User::class, 1);

        $this->withHeader('Authorization', 'Bearer ' . auth()->login($user))
            ->delete(route('auth.logout'))
            ->assertJsonPath('code', BizCode::SUCCESS->value);
    }

    public function testModifyPassword()
    {
        $params = [
            'name' => 'test',
            'password' => 'admin',
        ];

        $user = User::factory()->create($params);

        $this->assertDatabaseCount(User::class, 1);

        $this->actingAs($user)
            ->patchJson(route('auth.modify-password'), [
                'old_password' => 'admin',
                'password' => 'password',
            ])
            ->assertJsonPath('code', BizCode::SUCCESS->value);

        $this->postJson(route('auth.login'), [
            'name' => 'test',
            'password' => 'password',
        ])->assertJsonPath('code', BizCode::SUCCESS->value);
    }
}
