<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
   
    public function toArray($request)
    {
        $product = (new ProductResource($this->product_variant))->block_recurring_variants(true);
       
        // $product=[
        //     'slack' =>$this->product_variant->slack,
        //     'product_code' =>$this->product_variant->product_code,
        //     'name' =>$this->product_variant->name,
        //     'description' =>$this->product_variant->description,
        //     'quantity' =>$this->product_variant->quantity,
        //     'alert_quantity' =>$this->product_variant->alert_quantity,
        //     'purchase_amount_excluding_tax' =>$this->product_variant->purchase_amount_excluding_tax,
        //     'sale_amount_excluding_tax' =>$this->product_variant->sale_amount_excluding_tax,
        //     'sale_amount_including_tax' =>$this->product_variant->sale_amount_including_tax,
        //     'images' => ProductImageResource::collection($this->product_variant->product_images),
        //     'is_ingredient' =>$this->product_variant->is_ingredient,
        //     'is_ingredient_price' =>$this->product_variant->is_ingredient_price,
        //     'is_addon_product' => $this->product_variant->is_addon_product,
        //     'created_at_label' =>$this->product_variant->parseDate($this->product_variant->created_at),
        //     'updated_at_label' =>$this->product_variant->parseDate($this->product_variant->updated_at),
        // ];
       
        $variant_option = $this->variant_option;

        return [
            'slack' => $this->slack,
            'variant_code' => $this->variant_code,
            // 'product' => $product,
            'variant_option' => $variant_option,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
