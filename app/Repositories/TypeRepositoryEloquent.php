<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TypeRepository;
use App\Entities\Type;

class TypeRepositoryEloquent extends BaseRepository implements TypeRepository
{

    protected $rules = [
        'company_id'      => 'required',
        'name'      => 'min:3|required',
        ];

    public function model()
    {
        return Type::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    public function results($filters = array())
    {
        $types = $this->scopeQuery(function ($query) use ($filters) {
            
            if (!empty($filters['company-id'])) {
                $query = $query->where('company_id', $filters['company-id']);
            }
            if (!empty($filters['name'])) {
                $query = $query->where('name', $filters['name']);
            }

            $query = $query->orderBy($filters['sort'], $filters['order']);
            
            return $query;
        })->paginate($filters['paginate']);
        
        return $types;
    }
}