<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $state = [];
    public $photo;

    public function mount()
    {
        $this->state = Auth::user()->toArray();
    }

    public function updateProfileInformation()
    {
        /** @var \App\Models\User $user **/
        $this->validate([
            'photo' => 'nullable|image|max:1024', // Validación para la imagen
        ]);

        if ($this->photo) {
            $path = $this->photo->store('profile_images', 'public');

            $user = Auth::user();

            if ($user->image) {
                Storage::disk('public')->delete($user->image->path);
                $user->image->update(['path' => $path]);
            } else {
                $user->image()->create(['path' => $path]);
            }

            // Si deseas mantener el soporte para la columna 'profile_photo_path' de Jetstream:
            $user->forceFill([
                'profile_photo_path' => null,
            ])->save();
        }

        Auth::user()->forceFill([
            'name' => $this->state['name'],
            'email' => $this->state['email'],
        ])->save();

        $this->emit('saved');
    }

    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}
