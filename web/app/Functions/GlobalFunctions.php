<?php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use App\Models\User;

    function hasPermission($permission, $id_user=null) {
        if ($id_user == null) {
            $user = Auth::user();
        } else {
            $user = User::find($id_user);
        }
        return $permission == $user->role;
    }
