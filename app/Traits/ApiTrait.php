<?php

namespace App\Traits;

use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\BSON\ObjectId;

trait ApiTrait{
    public function scopeFilter(Builder $query)
    {
        if(empty($this->allowFilter) || empty(request('filter'))) {
            return $query;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);
        $allowFilterId = collect($this->allowFilterId);
        foreach ($filters as $filter => $value) {
            if($allowFilterId->contains($filter)) {
                $query->where($filter, new ObjectId($value));
            }
            else if($allowFilter->contains($filter)) {
                $query->where($filter,'LIKE', "%$value%");
            }
        }

    }

    public function scopeSort(Builder $query)
    {
        if(empty($this->allowSort) || empty(request('sort'))) {
            return $query;
        }

        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);
        foreach ($sortFields as $sortField) {

            $direction = 'asc';
            
            if(substr($sortField, 0, 1) == '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if($allowSort->contains($sortField)) {
                $query->orderBy($sortField, $direction);

            }

        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if(request('perPage')) {
            $perPage = intval(request('perPage'));
            if($perPage){
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}