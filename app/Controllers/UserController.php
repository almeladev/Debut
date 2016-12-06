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
        // Recuerda validar
//        $user = new User([
//            'name'     => $this->request->input('name'),
//            'email'    => $this->request->input('email'),
//            'password' => md5($this->request->input('pass'))
//        ]);  
        // Error (NO SE PUEDE CREAR UN USUARIO SIN CONTRASEÑA, CAMPO OBLIGATORIO, CONTROLAR)
        $user = new User([
            'name'     => "New2",
            'email'    => "New2@gmail.com",
//            'password' => '123'
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
        
        // Recuerda validar
//        $user->name     = $this->request->input('name'); // --> Si por casualidad no quieres actualizar un campo, no hace falta añadirlo.
//        $user->email    = $this->request->input('email');
//        if (! empty($this->request->input('pass'))) {
//            $user->password = md5($this->request->input('pass'));
//        }
//        $form = [
//            'name'     => $this->request->input('name'),
//            'email'    => $this->request->input('email'),
//            'password' => md5($this->request->input('pass'))
//        ];
        
//        $user->noexiste = "Esta variable no existe"; // El campo "noexiste" no existe en la tabla de usuarios, luego no se almacenará.
        
        // Y si ... ?
//        $form = [
//            'name'     => $this->request->input('name'),
//            'email'    => $this->request->input('email'),
//            //'password' => md5($this->request->input('pass'))
//        ];
//        $user->password = md5($this->request->input('pass'));
        // Debería actualizarse también la pass ??
        
        // Desordenados OK
//        $form = [
//            'name'     => $this->request->input('name'),
//            'password' => md5($this->request->input('pass')),
//            'email'    => $this->request->input('email'),     
//        ];
//        $user->name     = $this->request->input('name'); // --> Si por casualidad no quieres actualizar un campo, no hace falta añadirlo.
//        $user->password = md5($this->request->input('pass'));
//        $user->email    = $this->request->input('email');
        
        $form = [
            'name'     => $this->request->input('name'),
            'email'    => $this->request->input('email'),
            'password' => md5($this->request->input('pass'))
        ];
            
        if ($user->update($form)) {
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
