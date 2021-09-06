<?php
namespace transformer;
use Post;
use League\Fractal\TransformerAbstract;
class TitleTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'comment','user'
    ];


    public function transform($post)
    {
      
        return [
            'id'            => $post->id,
            'user_id'         => $post->user_id,
            'title'         =>  $post->title,
            'description'   =>  $post->description,
            'created_at' => $post->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $post->updated_at->format('Y-m-d H:i:s')
         ];
    }


    public function includeComment($post)
    {
        $comment = $post->comment;
        if($comment){
        return $this->collection($comment, new CommentTransformer);
        }
    }

    public function includeUser($post) 
    {
       
        $user = $post->users;
        if($user){
        return $this->item($user, new UserTransformer);
       
        }
    }
}