<?php

namespace App\Http\Controllers\API;

use App\Exceptions\OrderItem\AllOrderItemException;
use App\Exceptions\OrderItem\CreateOrderItemException;
use App\Exceptions\OrderItem\DeletedOrderItemException;
use App\Exceptions\OrderItem\UpdateOrderItemException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use App\Services\OrderItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderItemController extends Controller
{
    private $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService; 
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->orderItemService->all();
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
            'order_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->orderItemService->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->orderItemService->find($id);
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
        return $this->orderItemService->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->orderItemService->delete($id);
    }
}
