<?php

namespace App\Transformers;

use App\Models\Product;
use Flugg\Responder\Transformers\Transformer;

class ProductTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param \App\Models\Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id' => $product->ID,
            'name',
            'slug',
            'permalink',
            'date_created',
            'date_created_gmt',
            'date_modified',
            'date_modified_gmt',
            'type',
            'status',
            'featured',
            'catalog_visibility',
            'description',
            'short_description',
            'sku',
            'price',
            'regular_price',
            'sale_price',
            'date_on_sale_from',
            'date_on_sale_from_gmt',
            'date_on_sale_to',
            'date_on_sale_to_gmt',
            'on_sale',
            'purchasable',
            'total_sales',
            'virtual',
            'downloadable',
            'downloads',
            'download_limit',
            'download_expiry',
            'external_url',
            'button_text',
            'tax_status',
            'tax_class',
            'manage_stock',
            'stock_quantity',
            'in_stock',
            'backorders',
            'backorders_allowed',
            'backordered',
            'sold_individually',
            'weight',
            'dimensions',
            'shipping_required',
            'shipping_taxable',
            'shipping_class',
            'shipping_class_id',
            'reviews_allowed',
            'average_rating',
            'rating_count',
            'upsell_ids',
            'cross_sell_ids',
            'parent_id',
            'purchase_note',
            'categories',
            'tags',
            'images',
            'attributes',
            'default_attributes',
            'variations',
            'grouped_products',
            'menu_order'
        ];
    }
}
