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

            View::template('users/index.html', [
                'users' => $users,
            ]);

        } else {
            return redirect('/');
        }
        
    }

    /**
     * Muestra un formulario para crear un nuevo
     * usuario
     *
     * @return void
     */
    public function create()
    {
        View::template('users/create.html');
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
        $user->email    = $this->request->input('email');
        $user->name     = $this->request->input('name');
        $user->lastname = $this->request->input('lastname');
        $user->username = $this->request->input('username');
        $user->password = md5($this->request->input('pass'));
        $user->age      = $this->request->input('age');
        
        if ($user->save()) {
            return redirect('/users');
        } else {
            throw new \Exception('No se ha podido crear el usuario', 500);
        }
    }

    /**
     * Muestra un formulario con los datos del
     * usuario indicado
     *
     * @param  int $id El identificador
     *
     * @return void
     */
    public function edit($id)
    {
        $user = User::find($id);
        
        View::template('users/edit.html', [
            'user' => $user,
        ]);
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

        $prueba = $_SERVER['SERVER_NAME'];
        
        // Recuerda validar
        $user->email    = $this->request->input('email');
        $user->name     = $this->request->input('name');
        $user->lastname = (empty($this->request->input('lastname'))) ? null : $this->request->input('lastname');
        $user->username = $this->request->input('username');
        $user->password = md5($this->request->input('pass'));
        $user->age      = (empty($this->request->input('age'))) ? null : $this->request->input('age');
        
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
