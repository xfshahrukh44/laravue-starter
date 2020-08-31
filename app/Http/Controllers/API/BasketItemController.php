<?php

namespace App\Http\Controllers\API;

use App\Exceptions\BasketItem\AllBasketItemException;
use App\Exceptions\BasketItem\CreateBasketItemException;
use App\Exceptions\BasketItem\DeletedBasketItemException;
use App\Exceptions\BasketItem\UpdateBasketItemException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BasketItemService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BasketItemController extends Controller
{
    private $basketItemService;

    public function __construct(BasketItemService $basketItemService)
    {
        $this->basketItemService = $basketItemService; 
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->basketItemService->all();
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
            'basket_id' => 'required',
            'item_id' => 'required',
            ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->basketItemService->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->basketItemService->find($id);
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
        return $this->basketItemService->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->basketItemService->delete($id);
    }
}
