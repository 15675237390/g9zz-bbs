<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Date: 2017/4/12
 * Time: 下午4:12
 */

namespace App\Transformers;


use App\Models\User;

class UserTransformer extends BaseTransformer
{
    public function transform(User $user)
    {
        $return =  [
            'hid' => $user->hid,
            'name' => $user->name,
            'mobile' => $user->mobile,
            'email' => $user->email,
        ];

        if ($user->role){
            foreach ($user->role as $value) {
                $return['role'][] = [
                    'name' => $value->name,
                    'displayName' => $value->display_name,
                    'description' => $value->description
                ];
            }
        }
        return $return;
    }
}