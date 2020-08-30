<?php

namespace App\Http\Controllers\API;

use App\Exceptions\User\AllUserException;
use App\Exceptions\User\CreateUserException;
use App\Exceptions\User\DeletedUserException;
use App\Exceptions\User\UpdateUserException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use View;

class AuthController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService; 
        $this->middleware('auth:api', ['except' => ['login', 'store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->userService->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required',
            ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->userService->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->userService->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->userService->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->userService->delete($id);
    }

    public function login(Request $request)
    {
        $data = $this->userService->login($request->only('email', 'password'));
        $data = $data->getData();
        return response()->json($data);
    }

    public function logout()
    {
        return $this->userService->logout();
    }

    public function me()
    {
        return $this->userService->me();
    }
}
