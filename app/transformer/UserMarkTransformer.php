<?php
namespace transformer;
use User;
use League\Fractal\TransformerAbstract;

class UserMarkTransformer extends TransformerAbstract
{

   


    public function transform($user)
    {
      
        return [
            'id'            => $user->id,
            'first_name'         =>  $user->first_name,
            'last_name'         =>  $user->last_name,
            
         ];
    }


}