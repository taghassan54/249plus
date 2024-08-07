<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductResourceWithoutRelations;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'data' => request()->has('withRelations') && (request()->withRelations==false || request()->withRelations=="false")?ProductResourceWithoutRelations::collection($this->collection) : ProductResource::collection($this->collection),
            'links' => [
               'has_more_items' => $this->hasMorePages()??false,
               'current_page' => $this->currentPage()??1,
               'next_page' => $this->nextPageUrl()??'',
               'total_records' => $this->total()??0
            ]
        ];
    }
}
