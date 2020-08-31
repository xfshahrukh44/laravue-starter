<?php

namespace App\Repositories;

use App\Exceptions\Order\AllOrderException;
use App\Exceptions\Order\CreateOrderException;
use App\Exceptions\Order\UpdateOrderException;
use App\Exceptions\Order\DeletedOrderException;
use App\Models\Order;
use App\User;
use Illuminate\Support\Facades\DB;

abstract class OrderRepository implements RepositoryInterface
{
    /**
     * @var Order
     */
    private $model;
    private $User;
    /**
     * @var Group
     */

    /**
     * OrderRepository constructor.
     * @param Order $banner
     *
     * @param Group $group
     * @author Zohaib Hassan
     */
    public function __construct(Order $order, User $user)
    {
        $this->model = $order;
        $this->User = $user;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws CreateOrderException
     */

    public function create(array $data)
    {
        try {
            $data['user_id'] = auth()->user()->id;
            $order = $this->model->create($data);
            return [
                'success' => true,
                'message' => 'Order created successfully',
                'AddedOrder' => $order,
            ];
        } 
        catch (\Exception $exception) {
            throw new CreateOrderException($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws DeletedOrderException
     */
    public function delete($id)
    {
        try {
            if(auth()->user()->type == 'superadmin')
            {
                if(!$temp = $this->model->find($id))
                {
                    return [
                        'success' => false,
                        'message' => 'Could`nt find order',
                    ];
                }
                $this->model->destroy($id);
                return [
                    'success' => true,
                    'message' => 'Deleted successfully',
                    'deletedOrder' => $temp,
                ];
            }
            else
            {
                return [
                    'success' => false,
                    'message' => 'Not allowed',
                ];
            }
        } 
        catch (\Exception $exception) {
            throw new DeletedOrderException($exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed|void
     * @throws UpdateOrderException
     */
    public function update(array $data, $id)
    {
        try {
            if(auth()->user()->type == 'superadmin')
            {
                if(!$this->User::find($id))
                {
                    return [
                        'success' => false,
                        'message' => 'Bad request',
                    ];
                }
                if(!$temp = $this->model->find($id))
                {
                    return [
                        'success' => false,
                        'message' => 'Could`nt find order',
                    ];
                }
                $temp->update($data);
                return [
                    'success' => true,
                    'message' => 'Updated successfully!',
                    'updatedOrder' => $temp,
                ];
            }
            else
            {
                return [
                    'success' => false,
                    'message' => 'Not allowed',
                ];
            }
        } 
        catch (\Exception $exception) {
            throw new UpdateOrderException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function find($id)
    {
        try {
            if(auth()->user()->type == 'superadmin')
            {
                $order = $this->model::find($id);
                if(!$order)
                {
                    return [
                        'success' => false,
                        'message' => 'Could`nt find order',
                    ];
                }
                return [
                    'success' => true,
                    'order' => $order,
                ];
            }
            else
            {
                $order = $this->model::find($id);
                if(!$order || $order->user_id != auth()->user()->id)
                {
                    return [
                        'success' => false,
                        'message' => 'Could`nt find order',
                    ];
                }
                return [
                    'success' => true,
                    'order' => $order,
                ];
            }
        } 
        catch (\Exception $exception) {

        }
    }

    /**
     * @return Order[]|Collection|mixed
     * @throws AllOrderException
     */
    public function all()
    {
        try {
            if(auth()->user()->type == 'superadmin')
            {
                return $this->model::all();
            }
            else
            {
                return $this->model::where('user_id', auth()->user()->id)->get();
            }
        } 
        catch (\Exception $exception) {
            throw new AllOrderException($exception->getMessage());
        }
    }
}