<?php

namespace app\Controllers;

use core\Auth;
use core\Controller;
use app\Models\Post;
use core\Http\Request;

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
        }
        
        return redirect('/');
    }

    /**
     * Obtiene los datos de un formulario y crea el
     * post
     *
     * @return void
     */
    public function store(Request $request)
    { 
        $post = new Post([
            'title'   => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => Auth::user()->id
        ]);     
        
        if ($post->save()) {
            return redirect()->back();
        } else {
            return redirect()->back()->with('danger', $post->getErrors());
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
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        
        // Recuerda validar
        $post->title   = $request->input('title');
        $post->content = $request->input('content');
        
        if ($post->update()) {
            return redirect()->back();
        } else {
            return redirect()->back()->with('danger', $post->getErrors());
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
            return redirect()->back();
        } else {
            return redirect()->back()->with('danger', $post->getErrors());
        }
    }
}

