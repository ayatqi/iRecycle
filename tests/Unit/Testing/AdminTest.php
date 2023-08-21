<?php

namespace Tests\Feature;

require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /* checks whether a user with the 'user' role can successfully access their profile page */
    public function testAdminProfileAccess(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
    }
    /* verify the successful user login */
    public function testAdminLoginSuccessfully(): void
    {
        $admin = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gamil.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin); // Authenticate the user

        // Simulate a request, such as accessing a protected route
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200); // Check the expected response status
    }

    /* User edit their profile */
    public function testAdminEditProfile(): void
    {
        // Create a user
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Authenticate the user
        $this->actingAs($admin);

        // Simulate a request to edit the user's profile
        $response = $this->get(route('admin.profile'));

        // Assert that the edit profile page is accessible
        $response->assertStatus(200);

        // Simulate a request to update the user's profile
        $updateResponse = $this->post(route('admin.profile.store'), [
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '111',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Assert that the profile update is successful
        $updateResponse->assertStatus(302);
        $updateResponse->assertRedirect('/admin/profile');

        // Retrieve the updated user instance from the database
        $updatedAdmin = User::find($admin->id);

        // Assert that the updated profile information matches the submitted data
        $this->assertEquals('Admin', $updatedAdmin->name);
    }
    /* User deletes their profile */
    public function testUserDeletesProfile(): void
    {
        // Create a user
        $admin = User::factory()->create([
            'id' => '1',
            'role' => 'admin',
        ]);
        // Authenticate the user
        $this->actingAs($admin);

        // Delete the user's profile directly
        $admin->delete();
        // Check that the user's profile has been deleted from the database
        $this->assertDatabaseMissing('users', ['id' => $admin->id]);
    }
}
