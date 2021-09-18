<?php
namespace Traits;
use Carbon\Carbon;
use DB;
use Post;

trait PostTrait
{

    
    public function sort($posts ,$limit)
    {
        $sort_by = \input::get('sort_by') ?  :'posts.id';
		$sort_order = \input::get('sort_order') ? :'desc';
        $posts = Post::orderBy($sort_by, $sort_order)->Paginate($limit);

        return $posts;
    }
  
}
?>