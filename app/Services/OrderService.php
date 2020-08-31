<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Basket;


class OrderService extends OrderRepository
{
    public function convert_into_basket(array $data)
    {
        $new_basket = Basket::create([
                        'user_id' => auth()->user()->id,
                        'name' => $data['basket_name'],
                    ]);
        
        $order_items = DB::table('order_items')
                    ->where('order_id', $data['order_id'])
                    ->get();

        foreach($order_items as $order_item)
        {
            DB::table('basket_items')
            ->insert([
                'basket_id' => $new_basket->id,
                'item_id' => $order_item->item_id,
                'quantity' => $order_item->quantity,
            ]);
        }
        return $new_basket;
    }
}