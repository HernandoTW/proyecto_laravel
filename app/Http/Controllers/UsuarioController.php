<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return Usuario::all(); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuario', 
            'telefono' => 'required|string|max:20',
            'contraseña' => 'required|string|min:6',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'contraseña' => bcrypt($request->contraseña),
        ]);

        return response()->json($usuario, 201);
    }

    public function show($id)
    {
        return Usuario::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        
        $request->validate([
            'nombre' => 'string|max:255',
            'apellido' => 'string|max:255',
            'correo' => 'string|email|max:255|unique:usuario,correo,' . $usuario->id,
            'telefono' => 'string|max:20',
            'contraseña' => 'string|min:6',
        ]);

        $usuario->update($request->only('nombre', 'apellido', 'correo', 'telefono', 'contraseña'));

        return response()->json($usuario, 200);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json(null, 204);
    }
}
