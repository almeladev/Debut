<?php

namespace app\Controllers;

use app\Models\User;
use core\Auth;
use core\Controller;
use core\View;


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

            $users = User::all();

            View::template('users/index.twig.html', [
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
        $user = new User();     
        
        // Recuerda validar
        $user->name     = $this->request->input('name');
        $user->email    = $this->request->input('email');
        $user->password = md5($this->request->input('pass'));
        
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
        
        // Recuerda validar
        $user->name     = $this->request->input('name');
        $user->email    = $this->request->input('email');
        if (! empty($this->request->input('pass'))) {
            $user->password = md5($this->request->input('pass'));
        }
        
        if ($user->update()) {
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
