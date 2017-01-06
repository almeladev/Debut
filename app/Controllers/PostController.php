<?php

namespace app\Controllers;

use app\Models\Post;
use core\Auth;
use core\Controller;

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
            
            // Todos los posts con sus usuarios paginados (ver modelo de posts)
            $posts = Post::withUsers();
            
            return view('posts/index.twig', [
                'posts' => $posts,
            ]);
        } else {
            return redirect('/');
        }
    }

    /**
     * Obtiene los datos de un formulario y crea el
     * post
     *
     * @return void
     */
    public function store()
    { 
        $post = new Post([
            'title'   => $this->request->input('title'),
            'content' => $this->request->input('content'),
            'user_id' => Auth::user()->id
        ]);     
        
        if ($post->save()) {
            return redirect('/posts');
        } else {
            throw new \Exception('No se ha podido crear el post', 500);
        }
    }

    /**
     * Actualiza el post con los nuevos datos
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
     * Borra el post indicado
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

