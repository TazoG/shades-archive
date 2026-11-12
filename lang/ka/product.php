<?php

declare(strict_types=1);

return [
    'types' => [
        'sunglasses' => 'სათვალე',
        'watch' => 'საათი',
        'backpack' => 'ზურგჩანთა',
        'accessory' => 'აქსესუარი',
    ],
    'sections' => [
        'general' => 'ძირითადი ინფორმაცია',
        'specs' => 'ტექნიკური მახასიათებლები და არქივი',
        'pricing' => 'ფასწარმოქმნა და მარაგი',
        'media' => 'სტუდიური ფოტოგალერეა',
    ],
    'fields' => [
        'brand' => 'ბრენდი',
        'category' => 'კატეგორია',
        'status' => 'სტატუსი',
        'era' => 'ერა / პერიოდი',
        'frame_color' => 'ჩარჩოს ფერი',
        'lens_color' => 'ლინზის ფერი',
        'condition_grade' => 'მდგომარეობა (Condition)',
        'product_type' => 'პროდუქტის ტიპი',
        'sku' => 'SKU კოდი',
        'slug' => 'URL Slug',
        'model_code' => 'მოდელის კოდი',
        'serial_number' => 'სერიული ნომერი',
        'release_year' => 'გამოშვების წელი',
        'name' => 'დასახელება',
        'subtitle' => 'ქვესათაური',
        'description' => 'აღწერა',
        'provenance_notes' => 'კომპლექტაცია და ისტორია',
        'price_gel' => 'ფასი (GEL ₾)',
        'price_usd' => 'ფასი (USD $ — არასავალდებულო)',
        'compare_at_price_gel' => 'ძველი ფასი (GEL ₾)',
        'stock_quantity' => 'მარაგი',
        'is_one_of_one' => '1-of-1 უნიკალური ეგზემპლარი',
        'is_featured' => 'რჩეული (Featured)',
        'status_code' => 'სტატუსის კოდი',
        'status_color' => 'სტატუსის ფერი',
        'is_available' => 'ხელმისაწვდომია შესაძენად',
        'is_archived' => 'არქივის სტატუსი (Sold / Vault)',
    ],
    'modals' => [
        'create_status' => 'ახალი სტატუსის დამატება',
        'edit_status' => 'სტატუსის რედაქტირება',
    ],
    'notifications' => [
        'status_deleted' => 'სტატუსი წარმატებით წაიშალა.',
    ],
    'exceptions' => [
        'cannot_delete_default_status' => 'ნაგულისხმევი სტატუსის წაშლა აკრძალულია.',
        'status_in_use' => 'სტატუსი გამოიყენება პროდუქტებში და ვერ წაიშლება.',
    ],
];
