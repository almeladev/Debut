<?php

namespace app\Controllers;

use app\Models\User;
use core\Auth;
use core\Controller;
use core\Http\Request;

class UserController extends Controller
{

    /**
     * Muestra la lista de usuarios
     *
     * @return void
     */
    public function index()
    {
        if (Auth::check()) {
            
            // Todos los usuarios paginados (10 por página por defecto)
            $users = User::paginate();
            
            return view('users/index.twig', [
                'users' => $users
            ]);
        }
        
        return redirect('/');
    }

    /**
     * Obtiene los datos de un formulario y crea el
     * usuario
     *
     * @return void
     */
    public function store(Request $request)
    {        
        // Recuerda validar
        $user = new User([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => encrypt($request->input('password'))
        ]);
        
        if ($user->save()) {     
            return redirect()->back();
        } else {
            return redirect()->back()->with('danger', $user->getErrors());
        }
    }

    /**
     * Actualiza el usuario con los nuevos datos
     * pasados
     *
     * @param  int $id El identificador
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (\core\Hash::check($request->input('oldpassword'), $user->password)) {
            $newData = [
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'password' => encrypt($request->input('newpassword'))
            ];
        
            if ($user->update($newData)) { 
                return redirect('/users');
            } else {
                return redirect()->back()->with('danger', $user->getErrors());
            }
        }
        
        return redirect()->back()->with('danger', 'La anterior contraseña no coincide');
    }

    /**
     * Borra el usuario indicado
     *
     * @param  int $id El identificador
     *
     * @return void
     */
    public function destroy($id)
    {
        $user = User::find($id);
        
        if ($user->delete()) {
            return redirect()->back();
        } else {
            return redirect()->back()->with('danger', $user->getErrors());
        }
    }
}
