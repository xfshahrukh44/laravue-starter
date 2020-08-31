<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Unit\AllUnitException;
use App\Exceptions\Unit\CreateUnitException;
use App\Exceptions\Unit\DeletedUnitException;
use App\Exceptions\Unit\UpdateUnitException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use App\Services\UnitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    private $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService; 
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->unitService->all();
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
            'name' => 'required|string|max:50',
            'placeholder' => 'required|string|max:50',
            'slug' => 'required|string|max:20',
            ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->unitService->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->unitService->find($id);
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
        return $this->unitService->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->unitService->delete($id);
    }
}
