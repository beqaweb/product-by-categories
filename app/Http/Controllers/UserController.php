<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * List the users with their roles
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexRoles()
    {

        $users = User::with('roles')
            ->whereNotIn('id', [1, auth()->user()->id])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        $roles = Role::all();

        return view('admin.roles.assign', compact('users', 'roles'));
    }

    /**
     * Update user roles
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRoles(Request $request)
    {
        $roles = [];
        if ($request->has('roles')) {
            foreach ($request['roles'] as $userId => $roleId) {
                if (!array_key_exists($roleId, $roles)) { // kind of a cache for performance
                    $roles[$roleId] = Role::query()->find($roleId);
                }

                User::query()->find($userId)->syncRoles($roles[$roleId]);
            }
        }

        return redirect()->route('roleList', [
            'page' => $request['page']
        ]);
    }
}
