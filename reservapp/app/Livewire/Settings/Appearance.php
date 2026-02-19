<?php

namespace App\Livewire\Settings;

use Livewire\Component;

/**
 * NOTA: Esta funcionalidad se ha movido a Profile.php
 * 
 * El selector de apariencia (tema claro/oscuro/sistema) ahora está integrado
 * en la página de perfil. No requiere lógica backend ya que Flux UI maneja
 * el tema directamente en el navegador con Alpine.js y localStorage.
 * 
 * @see resources/views/livewire/settings/profile.blade.php (sección Apariencia)
 */
class Appearance extends Component
{
    // Funcionalidad movida a Profile.php
}
