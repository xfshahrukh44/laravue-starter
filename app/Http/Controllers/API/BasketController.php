<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Basket\AllBasketException;
use App\Exceptions\Basket\CreateBasketException;
use App\Exceptions\Basket\DeletedBasketException;
use App\Exceptions\Basket\UpdateBasketException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use App\Services\BasketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    private $basketService;

    public function __construct(BasketService $basketService)
    {
        $this->basketService = $basketService; 
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->basketService->all();
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
            'name' => 'required|string|max:100',
            ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->basketService->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->basketService->find($id);
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
        return $this->basketService->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->basketService->delete($id);
    }
}
