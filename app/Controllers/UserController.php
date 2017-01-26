<?php

namespace app\Controllers;

use core\Auth;
use core\Hash;
use core\Controller;
use app\Models\User;
use core\Http\Request;

class UserController extends Controller
{

    /**
     * Muestra la lista de usuarios
     *
     * @return \core\Routing\Redirector
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
     * @param \core\Http\Request $request
     * 
     * @return \core\Routing\Redirector
     */
    public function store(Request $request)
    {        
        $user = new User([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => encrypt($request->input('password'))
        ]);
        
        if ($user->save()) { 
            
            if ($request->input('avatar')['name']) {
                
                $avatar_name = $user->id . '-' . date("Y-m-d") . '-' . $request->input('avatar')['name'];              
                $user->avatar = $avatar_name;
                $user->update();
                
                // Finalmente guardo el archivo con su nombre
                move_uploaded_file($request->input('avatar')['tmp_name'], ROOT . 'public/images/users/' . $avatar_name);
            }
            
            return redirect()->back();
        } else {
            return redirect()->back()->with('danger', $user->getErrors());
        }
    }

    /**
     * Actualiza el usuario con los nuevos datos
     * pasados
     *
     * @param \core\Http\Request $request
     * @param  int $id El identificador
     *
     * @return \core\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (Hash::check($request->input('password'), $user->password)) {
            
            // Se comprueba si se ha introducido una nueva contraseña,
            // si no se ha introducido no se modificará la password
            if ($request->input('newpassword')) {
                $newData = [
                    'name'     => $request->input('name'),
                    'email'    => $request->input('email'),
                    'password' => encrypt($request->input('newpassword'))
                ];
            } else {
                $newData = [
                    'name'     => $request->input('name'),
                    'email'    => $request->input('email'),
                ];
            }
            
            if ($user->update($newData)) { 
                
                if ($request->input('avatar')['name']) {
                
                    $avatar_name = $user->id . '-' . date("Y-m-d") . '-' . $request->input('avatar')['name'];              
                    $user->avatar = $avatar_name;
                    $user->update();

                    // Finalmente guardo el archivo con su nombre
                    move_uploaded_file($request->input('avatar')['tmp_name'], ROOT . 'public/images/users/' . $avatar_name);
                }
            
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
     * @return \core\Routing\Redirector
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
