<?php
namespace App\Traits;

trait HasProfileImage
{
    // Acceso para obtener la imagen
    public function getProfileImageAttribute()
    {
        if ($this->images()->exists()) {
            return $this->images()->first()->path; //ruta de imagen
        }

        //imagen por defecto en caso de no encontrar imagen
        return $this->getDefaultImagePath();
    }

    protected function getDefaultImagePath()
    {
        // Personalizar la imagen por defecto según el modelo
        if ($this instanceof \App\Models\User) {
            return 'path/to/default/user-image.png';
        } elseif ($this instanceof \App\Models\Product) {
            return 'path/to/default/product-image.png';
        }

        return 'path/to/default/image.png';
    }
}