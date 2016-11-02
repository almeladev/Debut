<?php

namespace app\Controllers;

use app\Models\Post;
use app\Models\User;
use core\Auth;
use core\Controller;
use core\View;


class PostController extends Controller
{

    /**
     * Muestra la lista de posts
     *
     * @return void
     */
    public function index()
    {
        if (Auth::check()) {
            
            // Todos los posts de los usuarios
            $posts = User::posts();

            View::template('posts/index.html', [
                'posts' => $posts,
            ]);

        } else {
            return redirect('/');
        }
        
    }
    
    /**
     * Muestra un formulario para crear un nuevo
     * post
     *
     * @return void
     */
    public function create()
    {
        View::template('posts/create.html');
    }

    /**
     * Obtiene los datos de un formulario y crea el
     * post
     *
     * @return void
     */
    public function store()
    {
        $post = new Post();     
        
        // Recuerda validar
        $post->title   = $this->request->input('title');
        $post->content = $this->request->input('content');
        $post->user_id = Auth::user()->id;
        
        if ($post->save()) {
            return redirect('/posts');
        } else {
            throw new \Exception('No se ha podido crear el post', 500);
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
        $post = Post::find($id);
        
        View::template('posts/edit.html', [
            'post' => $post,
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
        $post = Post::find($id);
        
        // Recuerda validar
        $post->title   = $this->request->input('title');
        $post->content = $this->request->input('content');
        
        if ($post->update()) {
            return redirect('/posts');
        } else {
            throw new \Exception('No se ha podido actualizar el posts', 500);
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
        $post = Post::find($id);
        
        if ($post->delete()) {
            return redirect('/posts');
        } else {
            throw new \Exception('No se ha podido borrar el post', 500);
        }
    }
}

