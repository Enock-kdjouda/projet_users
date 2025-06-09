<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\UseCases\User\CreateUserUseCase;
use App\UseCases\User\UpdateUserUseCase;
use App\UseCases\User\DeleteUserUseCase;


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

  public function store(StoreUserRequest $request, CreateUserUseCase $useCase) {
      $user = $useCase->execute($request->validated());
      return $user;
  }
  public function show($id) {
     return User::findOrFail($id);
  }
  public function update(UpdateUserRequest $request,$id, UpdateUserUseCase $useCase) {
      $user = User::findOrFail($id);
      $updatedUser = $useCase->execute($user, $request->validated());
      return $updatedUser;
  }
  public function destroy($id,DeleteUserUseCase $useCase) {
      $user = User::findOrFail($id);
      $useCase->execute($user);
      return response()->json(null, 204);
  }

}
