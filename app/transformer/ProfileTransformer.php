<?php
namespace transformer;
use user_profile;
use League\Fractal\TransformerAbstract;
class ProfileTransformer extends TransformerAbstract
{

    // protected $availableIncludes = [
    //     'comment'
    // ];


    public function transform($profile)
    {
      
        return [
            // 'id'            => $profile->id,
            // 'user_id'         =>  $profile->user_id,
            'profile'         =>  $profile->profile,
            // 'created_at' => $profile->created_at->format('Y-m-d H:i:s'),
            // 'updated_at' => $profile->updated_at->format('Y-m-d H:i:s')
         ];
    }


    // public function includeComment($post)
    // {
    //     $comment = $post->comment;
    //     if($comment){
    //     return $this->collection($comment, new CommentTransformer);
    //     }
    // }
}