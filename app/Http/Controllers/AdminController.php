<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Muestra el panel de administración.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ejemplo: Obtén todos los usuarios registrados
        $users = User::all();

        return view('admin.index', compact('users'));
    }

    /**
     * Ejemplo de eliminación de un usuario.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
