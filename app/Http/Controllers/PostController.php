<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostLike;

class PostController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');

        $this->loggedUser = auth()->user();
    }

    public function like($id) {
        
        $array = ['error' => ''];
        // 1. Verificar se o POST existe
        $postExists = Post::find($id);

        if($postExists) {
            // 2. Verificar se eu já dei LIKE nesse post
            $isLiked = PostLike::where('id_post', $id)
            ->where('id_user', $this->loggedUser['id'])
            ->count();
            if($isLiked > 0) {
                // 2.1 Se sim, remover
                $pl = PostLike::where('id_post', $id)
                ->where('id_user', $this->loggedUser['id'])
                ->first();
                $pl->delete();

                $array['isLiked'] = false;
            } else {
                // 2.1 Se não, adicionar
                $newPostlike = new PostLike();
                $newPostlike->id_post = $id;
                $newPostlike->id_user = $this->loggedUser['id'];
                $newPostlike->created_at = date('Y-m-d H:i:s');
                $newPostlike->save();

                $array['isLiked'] = true;
            }

            $likeCount = PostLike::where('id_post', $id)->count();
            $array['likeCount'] = $likeCount;

        } else {
            $array['error'] = 'Post não existe!';
            return $array;
        }

        return $array;
    }
}

