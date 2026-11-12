<?php

declare(strict_types=1);

return [
    'types' => [
        'sunglasses' => 'Sunglasses',
        'watch' => 'Watch',
        'backpack' => 'Backpack',
        'accessory' => 'Accessory',
    ],
    'sections' => [
        'general' => 'General Information',
        'specs' => 'Technical Specifications & Provenance',
        'pricing' => 'Pricing & Inventory',
        'media' => 'Studio Gallery',
    ],
    'fields' => [
        'brand' => 'Brand',
        'category' => 'Category',
        'status' => 'Status',
        'era' => 'Era / Period',
        'frame_color' => 'Frame Color',
        'lens_color' => 'Lens Color',
        'condition_grade' => 'Condition Grade',
        'product_type' => 'Product Type',
        'sku' => 'SKU',
        'slug' => 'URL Slug',
        'model_code' => 'Factory Model Code',
        'serial_number' => 'Serial Number',
        'release_year' => 'Release Year',
        'name' => 'Name',
        'subtitle' => 'Subtitle',
        'description' => 'Description',
        'provenance_notes' => 'Provenance & Box Contents',
        'price_gel' => 'Price (GEL ₾)',
        'price_usd' => 'Price (USD $ — Optional)',
        'compare_at_price_gel' => 'Compare-at Price (GEL ₾)',
        'stock_quantity' => 'Stock Quantity',
        'is_one_of_one' => '1-of-1 Archive Piece',
        'is_featured' => 'Featured',
        'status_code' => 'Status Code',
        'status_color' => 'Status Color',
        'is_available' => 'Available Now',
        'is_archived' => 'Archive Vault Status',
    ],
    'modals' => [
        'create_status' => 'Add New Status',
        'edit_status' => 'Edit Status',
    ],
    'notifications' => [
        'status_deleted' => 'Status deleted successfully.',
    ],
    'exceptions' => [
        'cannot_delete_default_status' => 'Default status cannot be deleted.',
        'status_in_use' => 'Status is currently assigned to products and cannot be deleted.',
    ],
];
