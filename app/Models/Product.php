<?php

namespace App\Models;

/**
 * Class Product
 * @package App\Models
 */
class Product extends Post
{

    /**
     * The post type of model.
     *
     * @var  string
     */
    public $postType = ['product', 'product_variation'];

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $appends = [
        'type',
        'featured',
        'catalog_visibility',
        'sku',
        'price',
        'regular_price',
        'sale_price',
        'date_on_sale_from',
        'date_on_sale_to'
    ];
}
