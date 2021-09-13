<?php
namespace transformer;
use User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'profile','department'
    ];


    public function transform($user)
    {
      
        return [
            'id'            => $user->id,
            'first_name'         =>  $user->first_name,
            'last_name'         =>  $user->last_name,
            'email'   =>  $user->email,
            'is_active'   =>  $user->is_active,
            // 'profile'   => $user=  '/userprofile/'. $filename
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $user->updated_at->format('Y-m-d H:i:s')
         ];
    }


    public function includeProfile($user)
    {
        $rule = $user->profile;
        if($rule){
        return $this->item ($rule, new ProfileTransformer);
        }
    }

    public function includeDepartment($user)
    {
        $rule = $user->department;
        if($rule){
        return $this->collection ($rule, new DepartmentTransformer);
        }
    }
}