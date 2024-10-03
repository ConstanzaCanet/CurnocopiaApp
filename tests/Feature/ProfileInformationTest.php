<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_profile_information_is_available(): void
    {
        $this->actingAs($user = User::factory()->create());

        $component = Livewire::test(UpdateProfileInformationForm::class);

        $this->assertEquals($user->name, $component->state['name']);
        $this->assertEquals($user->email, $component->state['email']);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Agregamos last name
        $response = $this->put(route('user-profile-information.update'), [
            'name' => 'New Name',
            'last_name' => 'New Last Name',
            'email' => 'newemail@example.com',
        ]);

        $response->assertStatus(302);

        $this->assertEquals('New Name', $user->fresh()->name);
        $this->assertEquals('New Last Name', $user->fresh()->last_name);
        $this->assertEquals('newemail@example.com', $user->fresh()->email);
    }
}
