<?php

namespace App\Http\Resources;

use App\Category;
use App\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'part_number' => $this->part_number,
            'description' => $this->description,
            'category' => Category::find($this->category_id),
            'sub_category' => SubCategory::find($this->sub_category_id),
        ];
    }
}
