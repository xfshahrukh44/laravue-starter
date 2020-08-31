<?php

namespace App\Repositories;

use App\Exceptions\OrderItem\AllOrderItemException;
use App\Exceptions\OrderItem\CreateOrderItemException;
use App\Exceptions\OrderItem\UpdateOrderItemException;
use App\Exceptions\OrderItem\DeletedOrderItemException;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

abstract class OrderItemRepository implements RepositoryInterface
{
    /**
     * @var OrderItem
     */
    private $model;
    private $Order;
    private $Item;
    /**
     * @var Group
     */

    /**
     * OrderItemRepository constructor.
     * @param OrderItem $banner
     *
     * @param Group $group
     * @author Zohaib Hassan
     */
    public function __construct(OrderItem $orderItem, Order $order, Item $item)
    {
        $this->model = $orderItem;
        $this->Order = $order;
        $this->Item = $item;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws CreateOrderItemException
     */

    public function create(array $data)
    {
        // dd($this->model->create($data));
        try {
            $order = $this->Order::find($data['order_id']);
            $item = $this->Item::find($data['item_id']);
            if(!$order || !$item || $order->user_id != auth()->user()->id)
            {
                return [
                    'success' => false,
                    'message' => 'Bad request',
                ];
            }
            $orderItem = $this->model->create($data);
            return [
                'success' => true,
                'message' => 'OrderItem created successfully',
                'AddedOrderItem' => $orderItem,
            ];
        } 
        catch (\Exception $exception) {
            throw new CreateOrderItemException($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws DeletedOrderItemException
     */
    public function delete($id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find orderItem',
                ];
            }
            $temp->delete();
            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedOrderItem' => $temp,
            ];
        } 
        catch (\Exception $exception) {
            throw new DeletedOrderItemException($exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed|void
     * @throws UpdateOrderItemException
     */
    public function update(array $data, $id)
    {
        try {
            $order = $this->Order::find($data['order_id']);
            $item = $this->Item::find($data['item_id']);
            if(!$order || !$item || $order->user_id != auth()->user()->id)
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
                    'message' => 'Could`nt find orderItem',
                ];
            }
            $temp->update($data);
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updatedOrderItem' => $temp,
            ];
        } 
        catch (\Exception $exception) {
            throw new UpdateOrderItemException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function find($id)
    {
        try {
            $orderItem = $this->model::find($id);
            if(!$orderItem || $orderItem->order->user_id != auth()->user()->id)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find orderItem',
                ];
            }
            return [
                'success' => true,
                'orderItem' => $orderItem,
            ];
        } 
        catch (\Exception $exception) {

        }
    }

    /**
     * @return OrderItem[]|Collection|mixed
     * @throws AllOrderItemException
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
                return DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.user_id', auth()->user()->id)
                    ->where('order_items.deleted_at', NULL)
                    ->select('order_items.id', 'order_items.order_id', 'order_items.item_id', 'order_items.quantity', 'order_items.price')
                    ->get();
            }
            return $this->model::all();
        } 
        catch (\Exception $exception) {
            throw new AllOrderItemException($exception->getMessage());
        }
    }
}