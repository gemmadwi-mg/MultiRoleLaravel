<?php

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract { 
    public function transform(User $user) { 
        return [
            'id' => (int) $user->id,
            'email' => $user->email,
            'username' => $user->name
        ];
    }
}