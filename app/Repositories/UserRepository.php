<?php

namespace App\Repositories;

use App\Exceptions\User\AllUserException;
use App\Exceptions\User\CreateUserException;
use App\Exceptions\User\UpdateUserException;
use App\Exceptions\User\DeletedUserException;
use App\User;
use Illuminate\Support\Facades\DB;
use Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

abstract class UserRepository implements RepositoryInterface
{
    /**
     * @var User
     */
    private $model;
    /**
     * @var Group
     */

    /**
     * UserRepository constructor.
     * @param User $banner
     *
     * @param Group $group
     * @author Shahrukh Siddiqui
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws CreateUserException
     */

    public function create(array $data)
    {
        // dd($data['name']);
        try {
            $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            ]);
            
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user','token'),201);
        } 
        catch (\Exception $exception) {
            // throw new CreateUserException($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws DeletedUserException
     */
    public function delete($id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find user',
                ];
            }
            $this->model->destroy($id);
            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedUser' => $temp,
            ];
        } 
        catch (\Exception $exception) {
            throw new DeletedUserException($exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed|void
     * @throws UpdateUserException
     */
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find user',
                ];
            }
            // $this->model->findOrFail($id)->update($data);
            $temp->update($data);
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_user' => $temp,
            ];
        } 
        catch (\Exception $exception) {
            throw new UpdateUserException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function find($id)
    {
        try {
            // return $this->model::findOrFail($id);
            $user = $this->model::find($id);
            if(!$user)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find user',
                ];
            }
            return [
                'success' => true,
                'user' => $user,
            ];
        } 
        catch (\Exception $exception) {

        }
    }

    /**
     * @return User[]|Collection|mixed
     * @throws AllUserException
     */
    public function all()
    {
        try {
            return $this->model::all();
        } 
        catch (\Exception $exception) {
            throw new AllUserException($exception->getMessage());
        }
    }
}
