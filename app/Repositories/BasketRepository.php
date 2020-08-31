<?php

namespace App\Repositories;

use App\Exceptions\Basket\AllBasketException;
use App\Exceptions\Basket\CreateBasketException;
use App\Exceptions\Basket\UpdateBasketException;
use App\Exceptions\Basket\DeletedBasketException;
use App\Models\Basket;
use Illuminate\Support\Facades\DB;

abstract class BasketRepository implements RepositoryInterface
{

    /**
     * @var Basket
     */
    private $model;
    /**
    /**
     * BasketRepository constructor.
     * @param Basket $banner
     *
     * @param Group $group
     * @author Zohaib Hassan
     */
    public function __construct(Basket $Basket)
    {
        $this->model = $Basket;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws CreateBasketException
     */

    public function create(array $data)
    {
        try {
            $data['user_id'] = auth()->user()->id;
            $basket = $this->model->create($data);
            return [
                'success' => true,
                'message' => 'Basket created successfully',
                'basketAdded' => $basket,
            ];
        } 
        catch (\Exception $exception) {
            throw new CreateBasketException($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws DeletedBasketException
     */
    public function delete($id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find basket',
                ];
            }
            if(auth()->user()->type == 'superadmin')
            {
                $this->model->destroy($id);
                return [
                    'success' => true,
                    'message' => 'Deleted successfully',
                    'deletedBasket' => $temp,
                ];
            }
            else
            {
                if(!$temp->user_id == auth()->user()->id)
                {
                    return [
                        'success' => false,
                        'message' => 'Could`nt find basket',
                    ];
                }
                $this->model->destroy($id);
                return [
                    'success' => true,
                    'message' => 'Deleted successfully',
                    'deletedBasket' => $temp,
                ];
            }
        } 
        catch (\Exception $exception) {
            // throw new DeletedBasketException($exception->getMessage());
        }
        // dd($this->model->find($id));
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed|void
     * @throws UpdateBasketException
     */
    public function update(array $data, $id)
    {
        try {
            $temp = $this->model->find($id);
            if(!$temp || $temp->user_id != auth()->user()->id)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find basket',
                ];
            }
            $data['user_id'] = auth()->user()->id;
            $temp->update($data);
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updatedBasket' => $temp,
            ];
        } 
        catch (\Exception $exception) {
            throw new UpdateBasketException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function find($id)
    {
        try {
            if(!$basket = $this->model::find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find basket',
                ];
            }
            if(auth()->user()->type == 'superadmin')
            {
                return [
                    'success' => true,
                    'basket' => $basket,
                ];
            }
            else
            {
                if($basket->user_id != auth()->user()->id)
                {
                    return [
                        'success' => false,
                        'message' => 'Could`nt find basket',
                    ];
                }
                return [
                    'success' => true,
                    'basket' => $basket,
                ];
            }
            
        } 
        catch (\Exception $exception) {

        }
    }

    /**
     * @return Basket[]|Collection|mixed
     * @throws AllBasketException
     */
    public function all()
    {
        try {
            if(auth()->user()->type == "superadmin")
            {
                return $this->model::all();
            }
            else
            {
                return $this->model::where('user_id', auth()->user()->id)->get();
            }
        } 
        catch (\Exception $exception) {
            throw new AllBasketException($exception->getMessage());
        }
    }
}