<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;

/**
 * Backwards-compatible shim.
 * Some routes or cached references expect App\Http\Controllers\DoctorController.
 * Delegate to the admin namespaced controller to avoid Target class errors.
 */
class DoctorController extends AdminDoctorController
{
    // Intentionally empty — inherits all behavior from Admin\DoctorController
}
