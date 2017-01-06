<?php

namespace app\Controllers;

use app\Models\User;
use core\Auth;
use core\Controller;
use core\Paginator;

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
            
            // Todos los usuarios paginados 5 por página
            $users = User::paginate(5);
            
            return view('users/index.twig', [
                'users' => $users,
            ]);
        } else {
            return redirect('/');
        }
    }

    /**
     * Obtiene los datos de un formulario y crea el
     * usuario
     *
     * @return void
     */
    public function store()
    {
        // Recuerda validar
        $user = new User([
            'name'     => $this->request->input('name'),
            'email'    => $this->request->input('email'),
            'password' => encrypt($this->request->input('pass'))
        ]);
        
        if ($user->save()) {     
            return redirect('/users');
        } else {
            throw new \Exception('No se ha podido crear el usuario', 500);
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
    public function update($id)
    {
        $user = User::find($id);
        
//        $newData = [
//            'name'     => $this->request->input('name'),
//            'email'    => $this->request->input('email'),
//            'password' => encrypt($this->request->input('pass'))
//        ];
        $user->name  = $this->request->input('name');
        $user->email = $this->request->input('email');
        $user->password = encrypt($this->request->input('pass'));
        
        if ($user->update()) {
            
            $newData = [
                'name'     => 'Entro',
                'email'    => 'suputamadre@gmail.com',
                'password' => encrypt('secret')
            ];
            
            $user->update($newData);
            
            return redirect('/users');
        } else {
            throw new \Exception('No se ha podido actualizar el usuario', 500);
        }

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
            return redirect('/users');
        } else {
            throw new \Exception('No se ha podido borrar el usuario', 500);
        }
    }
}
