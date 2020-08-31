<?php

namespace App\Repositories;

use App\Exceptions\Unit\AllUnitException;
use App\Exceptions\Unit\CreateUnitException;
use App\Exceptions\Unit\UpdateUnitException;
use App\Exceptions\Unit\DeletedUnitException;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

abstract class UnitRepository implements RepositoryInterface
{

    /**
     * @var Unit
     */
    private $model;
    /**
    /**
     * UnitRepository constructor.
     * @param Unit $banner
     *
     * @param Group $group
     * @author Zohaib Hassan
     */
    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws CreateUnitException
     */

    public function create(array $data)
    {
        try {
            if(auth()->user()->type == 'superadmin')
            {
                $unit = $this->model->create($data);
                return [
                    'success' => true,
                    'message' => 'Unit created successfully',
                    'unitAdded' => $unit,
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
            throw new CreateUnitException($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * @throws DeletedUnitException
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
                        'message' => 'Could`nt find unit',
                    ];
                }
                $this->model->destroy($id);
                return [
                    'success' => true,
                    'message' => 'Deleted successfully',
                    'deletedUnit' => $temp,
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
            // throw new DeletedUnitException($exception->getMessage());
        }
        // dd($this->model->find($id));
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed|void
     * @throws UpdateUnitException
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
                        'message' => 'Could`nt find unit',
                    ];
                }
                $temp->update($data);
                return [
                    'success' => true,
                    'message' => 'Updated successfully!',
                    'updatedUnit' => $temp,
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
            throw new UpdateUnitException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function find($id)
    {
        try {
            $unit = $this->model::find($id);
            if(!$unit)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find unit',
                ];
            }
            return [
                'success' => true,
                'unit' => $unit,
            ];
        } 
        catch (\Exception $exception) {

        }
    }

    /**
     * @return Unit[]|Collection|mixed
     * @throws AllUnitException
     */
    public function all()
    {
        try {
            return $this->model::all();
        } 
        catch (\Exception $exception) {
            throw new AllUnitException($exception->getMessage());
        }
    }
}