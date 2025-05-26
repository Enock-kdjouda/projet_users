<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
  public function index(Request $request) {
    $query = User::query()->select('id', 'name', 'email', 'role');
    if ($search = $request->query('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    return $query->paginate(7);
}

  public function store(Request $req) {
    $data = $req->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required','role'=>'in:user,admin']);
    $data['password'] = bcrypt($data['password']);
    return User::create($data);
  }
  public function show($id) {
    return User::findOrFail($id);
  }
  public function update(Request $req,$id) {
    $user = User::findOrFail($id);
    $data = $req->validate([
      'name'=>'sometimes',
      'email'=>["sometimes","email",Rule::unique('users')->ignore($user->id)],
      'password'=>'sometimes',
      'role'=>'sometimes|in:user,admin'
    ]);
    if(isset($data['password'])) $data['password']=bcrypt($data['password']);
    $user->update($data);
    return $user;
  }
public function destroy($id) {
  User::findOrFail($id)->delete();
  return response()->json(null,204);
}

}
