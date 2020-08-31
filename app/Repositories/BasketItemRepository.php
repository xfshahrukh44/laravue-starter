<?php

namespace App\Repositories;

use App\Exceptions\BasketItem\AllBasketItemException;
use App\Exceptions\BasketItem\CreateBasketItemException;
use App\Exceptions\BasketItem\UpdateBasketItemException;
use App\Exceptions\BasketItem\DeletedBasketItemException;
use App\Models\BasketItem;
use App\Models\Basket;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

abstract class BasketItemRepository implements RepositoryInterface
{

    /**
     * @var BasketItem
     */
    private $BasketItem;
    private $Basket;
    private $Item;
    /**
    /**
     * BasketItemRepository constructor.
     * @param BasketItem $banner
     *
     * @param Group $group
     * @author Zohaib Hassan
     */
    public function __construct(BasketItem $basketItem, Basket $basket, Item $item)
    {
        $this->BasketItem = $basketItem;
        $this->Basket = $basket;
        $this->Item = $item;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws CreateBasketItemException
     */

    public function create(array $data)
    {
        try {
            $basket = $this->Basket::find($data['basket_id']);
            $item = $this->Item::find($data['item_id']);
            if(!$basket || !$item || $basket->user_id != auth()->user()->id)
            {
                return [
                    'success' => false,
                    'message' => 'Bad request',
                ];
            }
            dd($this->BasketItem::create($data));
            $basketItem = $this->BasketItem::create($data);
            return [
                'success' => true,
                'message' => 'BasketItem created successfully',
                'basketItemAdded' => $basketItem,
            ];
        } 
        catch (\Exception $exception) {
            throw new CreateBasketItemException($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws DeletedBasketItemException
     */
    public function delete($id)
    {
        try {
            $temp = $this->BasketItem->find($id);
            if(!$temp || $temp->basket->user_id != auth()->user()->id)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find basketItem',
                ];
            }
            $temp->delete();
            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedBasketItem' => $temp,
            ];
        } 
        catch (\Exception $exception) {
            // throw new DeletedBasketItemException($exception->getMessage());
        }
        // dd($this->BasketItem->find($id));
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed|void
     * @throws UpdateBasketItemException
     */
    public function update(array $data, $id)
    {
        try {
            $basket = $this->Basket::find($data['basket_id']);
            $item = $this->Item::find($data['item_id']);
            if(!$basket || !$item || $basket->user_id != auth()->user()->id)
            {
                return [
                    'success' => false,
                    'message' => 'Bad request',
                ];
            }
            if(!$temp = $this->BasketItem->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find basketItem',
                ];
            }
            $temp->update($data);
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updatedBasketItem' => $temp,
            ];
        } 
        catch (\Exception $exception) {
            throw new UpdateBasketItemException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function find($id)
    {
        try {
            $basketItem = $this->BasketItem::find($id);
            if(!$basketItem || $basketItem->basket->user_id != auth()->user()->id)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find basketItem',
                ];
            }
            return [
                'success' => true,
                'basketItem' => $basketItem,
            ];
        } 
        catch (\Exception $exception) {

        }
    }

    /**
     * @return BasketItem[]|Collection|mixed
     * @throws AllBasketItemException
     */
    public function all()
    {
        try {
            if(auth()->user()->type == 'superadmin')
            {
                return $this->BasketItem::all();
            }
            else
            {
                return DB::table('basket_items')
                    ->join('baskets', 'basket_items.basket_id', '=', 'baskets.id')
                    ->where('baskets.user_id', auth()->user()->id)
                    ->where('basket_items.deleted_at', NULL)
                    ->select('basket_items.id', 'basket_items.basket_id', 'basket_items.item_id', 'basket_items.quantity')
                    ->get();
            }
        } 
        catch (\Exception $exception) {
            throw new AllBasketItemException($exception->getMessage());
        }
    }
}