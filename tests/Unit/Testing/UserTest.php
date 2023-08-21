<?php

namespace Tests\Feature;

require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /* checks whether a user with the 'user' role can successfully access their profile page */
    public function testUserProfileAccess(): void
    {
        $User = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($User);

        $response = $this->get('/profile');

        $response->assertStatus(200);
    }
    /* verify the successful user login */
    public function testUserLoginSuccessfully(): void
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@gamil.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user); // Authenticate the user

        // Simulate a request, such as accessing a protected route
        $response = $this->get('/profile');

        $response->assertStatus(200); // Check the expected response status
    }
    /* registration of a new user */
    public function testUserRegisterSuccessfully(): void
    {
        // Simulate a request to register a new user
        $response = $this->post('/register', [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            '_token' => csrf_token(),
        ]);

        // Check the database for the new user
        $this->assertDatabaseHas('users', [
            'name' => 'test2',
            'email' => 'test2@gmail.com',
        ]);
        /*
         Status Code 200 (OK): This status code indicates that the request has been successfully processed,
         and the server is responding with the requested resource. It's commonly used for successful GET requests.
        */
        /*
        Status Code 302 (Found): This status code is used to indicate that the requested resource has been
        temporarily moved to a different location. The response typically includes a Location header that
        specifies the new location where the resource can be found. This is often used for redirects.
        */

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');

        // Retrieve the user instance from the database
        $registeredUser = User::where('email', 'test2@gmail.com')->first();

        // Authenticate the registered user
        $this->actingAs($registeredUser);

        // Check that the user is now authenticated
        $this->assertTrue(auth()->check());

        // You can also check other assertions about the authenticated user if needed
        $this->assertEquals('test2', auth()->user()->name);
        $this->assertEquals('test2@gmail.com', auth()->user()->email);
        $this->assertEquals('user', auth()->user()->role);
    }
    /* User edit their profile */
    public function testUserEditProfile(): void
    {
        // Create a user
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // Simulate a request to edit the user's profile
        $response = $this->get(route('profile.edit'));

        // Assert that the edit profile page is accessible
        $response->assertStatus(200);

        // Simulate a request to update the user's profile
        $updateResponse = $this->patch(route('profile.update'), [
            'name' => 'Updated Name',
        ]);

        // Assert that the profile update is successful
        $updateResponse->assertStatus(302);
        $updateResponse->assertRedirect('/profile');

        // Retrieve the updated user instance from the database
        $updatedUser = User::find($user->id);

        // Assert that the updated profile information matches the submitted data
        $this->assertEquals('Updated Name', $updatedUser->name);
    }
    /* User deletes their profile */
    public function testUserDeletesProfile(): void
    {
        // Create a user
        $user = User::factory()->create([
            'id' => '1',
            'role' => 'user',
        ]);
        // Authenticate the user
        $this->actingAs($user);

        // Delete the user's profile directly
        $user->delete();
        // Check that the user's profile has been deleted from the database
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
