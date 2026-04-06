<?php

namespace App\Http\Controllers;
use App\Models\Conexion;

use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function selectEst()
    {

        return response()->json(Conexion::all());
    }

    public function index()
    {
        $estudiantes = Conexion::all();
        return response()->json($estudiantes);
    }

    public function store(Request $request)
    {
        $estudiantes = Conexion::create([
            'cedula' => $request->txtCedula,
            'nombre' => $request->txtNombre,
            'apellido' => $request->txtApellido,
            'telefono' => $request->txtTelefono,
            'direccion' => $request->txtDireccion,
        ]);
        return response()->json('Estudiante creado');
    }

    public function update(Request $request)
    {
        $cedula = $request->txtCedula;
        $estudiantes = Conexion::find($cedula);
        $estudiantes->update([
            'nombre' => $request->txtNombre,
            'apellido' => $request->txtApellido,
            'telefono' => $request->txtTelefono,
            'direccion' => $request->txtDireccion,
        ]);
        return response()->json('Estudiante actualizado');
    }


    public function update1(Request $request, $cedula)
{
    $estudiantes = Conexion::where('cedula', $cedula)->firstOrFail();

    $request->validate([
        'txtNombre' => 'required|max:255|string',
        'txtApellido' => 'required',
        'txtTelefono' => 'required',
        'txtDireccion' => 'required',
    ]);

    $estudiantes->update([
        'nombre' => $request->txtNombre,
        'apellido' => $request->txtApellido,
        'telefono' => $request->txtTelefono,
        'direccion' => $request->txtDireccion,
    ]);

    return response()->json(['message' => 'Estudiante actualizado']);
}

    public function destroy(Request $request, $cedula)
    {
        $estudiantes = Conexion::where('cedula', $cedula)->firstOrFail();
        $estudiantes->delete();
        return response()->json('Estudiante eliminado');
    }



    
}
