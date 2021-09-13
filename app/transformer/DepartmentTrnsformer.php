<?php
namespace transformer;
use department;
use League\Fractal\TransformerAbstract;

class DepartmentTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'users'
    ];


    public function transform($dep)
    {
      
        return [
            'id'            => $dep->id,
            'name'         =>  $dep->name,
            'created_at' => $dep->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $dep->updated_at->format('Y-m-d H:i:s')
         ];
    }


    public function includeusers($dep)

    {
        $rule = $dep->users;
        if($rule){
        return $this->collection ($rule, new UserTransformer);
        }
    }
}