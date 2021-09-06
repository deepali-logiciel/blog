<?php
namespace transformer;
use comment;
use League\Fractal\TransformerAbstract;
class CommentTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'post','replies'
    ];
    public function transform($add)
    {
        return [
            'id'            =>$add->id,
            // 'post_id'         => $add->post_id,
            'parent_id'         => $add->parent_id,
            'comment'   => $add->comment,
            'created_at'    =>$add->created_at->format('Y-m-d-H:i:s'),
            'updated_at'    =>$add->updated_at->format('Y-m-d-H:i:s')
        ];
    }
    public function includePost( $add)
    {
        $post = $add->post;
         if($post){
        return $this->item($post, new TitleTransformer);
         }
    }

    public function includeReplies($add)
    {
        $reply = $add->replies;
         if($reply){
        return $this->collection($reply, new CommentTransformer);
         }
    }

}





