<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ProductResource;

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
            'data' => ProductResource::collection($this->collection),
            'links' => [
               'has_more_items' => $this->hasMorePages()??false,
               'current_page' => $this->currentPage()??1,
               'next_page' => $this->nextPageUrl()??'',
               'total_records' => $this->total()??0
            ]
        ];
    }
}
