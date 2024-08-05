<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductIngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ingredient_product = collect();
        $low_stock = 0;
        if(!empty($this->ingredient_product)){
            $ingredient_product = new ProductResource($this->ingredient_product);


            //TODO Test This
            // $ingredient=collect([
            //     'slack' =>$this->ingredient_product->slack,
            //     'product_code' =>$this->ingredient_product->product_code,
            //     'name' =>$this->ingredient_product->name,
            //     'description' =>$this->ingredient_product->description,
            //     'quantity' =>$this->ingredient_product->quantity,
            //     'alert_quantity' =>$this->ingredient_product->alert_quantity,
            //     'purchase_amount_excluding_tax' =>$this->ingredient_product->purchase_amount_excluding_tax,
            //     'sale_amount_excluding_tax' =>$this->ingredient_product->sale_amount_excluding_tax,
            //     'sale_amount_including_tax' =>$this->ingredient_product->sale_amount_including_tax,
            //     'images' => ProductImageResource::collection($this->ingredient_product->product_images),
            //     'is_ingredient' =>$this->ingredient_product->is_ingredient,
            //     'is_ingredient_price' =>$this->ingredient_product->is_ingredient_price,
            //     'is_addon_product' => $this->ingredient_product->is_addon_product,
            //     'created_at_label' =>$this->ingredient_product->parseDate($this->created_at),
            //     'updated_at_label' =>$this->ingredient_product->parseDate($this->updated_at),
            // ]);

            $low_stock = ($ingredient_product->quantity<=$ingredient_product->alert_quantity)?1:0;
            // $low_stock = ($ingredient->quantity<=$ingredient->alert_quantity)?1:0;
        }

        return [
            'slack' => $this->slack,
            // 'ingredient_product' => $ingredient_product,
            'quantity' => $this->quantity,
            'low_stock' => $low_stock,
            'measurement_unit' => new MeasurementUnitResource($this->measurement_unit),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
