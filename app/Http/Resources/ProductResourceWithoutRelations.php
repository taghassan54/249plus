<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResourceWithoutRelations extends JsonResource
{
    public function block_recurring_variants($value){
        $this->block_recurring_data = $value;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

       
        $ingredients = $this->ingredients?$this->ingredients->map(function($ingredient){
            $low_stock = 0;
            if(!empty($ingredient->ingredient_product)){
                $low_stock = ($ingredient->quantity<=$ingredient->alert_quantity)?1:0;
            }
return [
 'slack' => $ingredient->slack,
 'low_stock' => $low_stock,
];
        }):[] ;
     
        $ingredients_collection = collect($ingredients);
        $low_ingredient_stock = $ingredients_collection->map(function ($item, $key) {
            return $item['low_stock'];
        })->toArray();
        $low_ingredient_stock = (!empty($low_ingredient_stock))?in_array(1, $low_ingredient_stock):false;
       

        $addon_groups =$this->addon_groups? $this->addon_groups->map(function($addon_group){

            $addons=collect([]);
            foreach ($addon_group as $key => $addon) {
                $addons->push([
                    'slack' => $addon->slack,
                ]);
            }
            return $addons->toArray();

        }):[] ;

        $block_recurring_data = (isset($this->block_recurring_data))?$this->block_recurring_data:false;

        $variants = [];
        $variants_by_options = [];
        $variants_by_options_pos = [];
        $parent_variant_option = [];
        if($block_recurring_data == false){
            $product_variants = $this->product_variants($this->id);
            $variants = ProductVariantResource::collection($product_variants['product_variants']);

            $variants_collection = collect($variants);
            $variants_by_options = $variants_collection->groupBy('variant_option.label');
            $variants_by_options->toArray();
            
            $parent_variant_option = $product_variants['parent_variant_option'];

            $product_variants_pos = $this->product_variants($this->id, false);
            $variants_pos = ProductVariantResource::collection($product_variants_pos['product_variants']);

            $variants_pos_collection = collect($variants_pos);
            $variants_by_options_pos = $variants_pos_collection->sortBy('product.name')->groupBy('variant_option.label');
            $variants_by_options_pos->toArray();
        }

        return [
            'slack' => $this->slack,
            'product_code' => $this->product_code,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'alert_quantity' => $this->alert_quantity,
            'purchase_amount_excluding_tax' => $this->purchase_amount_excluding_tax,
            'sale_amount_excluding_tax' => $this->sale_amount_excluding_tax,
            'sale_amount_including_tax' => $this->sale_amount_including_tax,
            'category' => $this->category?$this->category->slack:'',
            'supplier' => $this->supplier?$this->supplier->slack:'',
            'tax_code' => $this->tax_code?$this->tax_code->slack:'',
            'discount_code' => $this->discount_code?$this->discount_code->slack:'',
            'images' => $this->product_images ? $this->product_images->map(function($image) {
                return [
                    'slack' => $image->slack,
                ];
            }) : [],
            'is_ingredient' => $this->is_ingredient,
            'is_ingredient_price' => $this->is_ingredient_price,
            'ingredients' => $ingredients,
            'ingredient_low_stock' => $low_ingredient_stock,
            'is_addon_product' =>  $this->is_addon_product,
            'addon_groups' => $addon_groups,
            'variants' => isset($variants)?$variants:NULL,
             'variants_by_options' => isset($variants_by_options)?$variants_by_options:NULL,
             'variants_by_options_pos' => isset($variants_by_options_pos)?$variants_by_options_pos:NULL,
            'parent_variant_option' => isset($parent_variant_option)?$parent_variant_option:NULL,
            'customizable' => ($addon_groups->isEmpty())?0:1,
            'status' =>$this->status_data?$this->status_data->value_constant:'',
            'detail_link' => (check_access(['A_DETAIL_PRODUCT'], true))?route('product', ['slack' => $this->slack]):'',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => $this->createdUser?$this->createdUser->slack:'',
            'updated_by' => $this->updatedUser?$this->updatedUser->slack:''
        ];
    }
}
