<?php

namespace App\Repositories;

use App\Exceptions\Item\AllItemException;
use App\Exceptions\Item\CreateItemException;
use App\Exceptions\Item\UpdateItemException;
use App\Exceptions\Item\DeletedItemException;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

abstract class ItemRepository implements RepositoryInterface
{
    /**
     * @var Item
     */
    private $model;
    /**
     * @var Group
     */

    /**
     * ItemRepository constructor.
     * @param Item $banner
     *
     * @param Group $group
     * @author Zohaib Hassan
     */
    public function __construct(Item $item)
    {
        $this->model = $item;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws CreateItemException
     */

    public function create(array $data)
    {
        try {
            if(auth()->user()->type == 'superadmin')
            {
                $item = $this->model->create($data);
                return [
                    'success' => true,
                    'message' => 'Basket created successfully',
                    'AddedItem' => $item,
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
            throw new CreateItemException($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws DeletedItemException
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
                        'message' => 'Could`nt find item',
                    ];
                }
                $this->model->destroy($id);
                return [
                    'success' => true,
                    'message' => 'Deleted successfully',
                    'deletedItem' => $temp,
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
            throw new DeletedItemException($exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed|void
     * @throws UpdateItemException
     */
    public function update(array $data, $id)
    {
        try {
            if(auth()->user()->type == 'superadmin')
            {
                if(!$temp = $this->model->find($id))
                {
                    return [
                        'success' => false,
                        'message' => 'Could`nt find item',
                    ];
                }
                $temp->update($data);
                return [
                    'success' => true,
                    'message' => 'Updated successfully!',
                    'updatedItem' => $temp,
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
            throw new UpdateItemException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function find($id)
    {
        try {
            $item = $this->model::find($id);
            if(!$item)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find item',
                ];
            }
            return [
                'success' => true,
                'item' => $item,
            ];
        } 
        catch (\Exception $exception) {

        }
    }

    /**
     * @return Item[]|Collection|mixed
     * @throws AllItemException
     */
    public function all()
    {
        try {
            return $this->model::all();
        } 
        catch (\Exception $exception) {
            throw new AllItemException($exception->getMessage());
        }
    }
}