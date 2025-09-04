<?php

return [
    'titles' => [
        'create' => 'إضافة تصنيف جديد',
        'edit' => 'تعديل التصنيف',
        'current_image' => 'الصورة الحالية',
    ],
    'fields' => [
        'parent_id' => 'التصنيف الأب',
        'name' => 'اسم التصنيف',
        'slug' => 'الرابط (Slug)',
        'description' => 'الوصف',
        'image' => 'الصورة',
        'is_active' => 'مفعل',
        'sort_order' => 'الترتيب',
    ],
    'help' => [
        'parent_id' => 'يمكنك تركه فارغ إذا لم يكن للتصنيف أب',
        'slug' => 'معرّف URL فريد',
        'image' => 'قم برفع صورة للتصنيف (JPG/PNG/WebP). الحجم الأقصى 3MB',
        'image_current_prefix' => 'الصورة الحالية: ',
    ],
    'actions' => [
        'save' => 'حفظ',
        'remove' => 'حذف',
        'confirm_remove' => 'هل أنت متأكد أنك تريد حذف هذا التصنيف؟',
    ],
    'toast' => [
        'saved' => 'تم حفظ التصنيف بنجاح',
        'deleted' => 'تم حذف التصنيف بنجاح',
        'delete_failed' => 'فشل في حذف التصنيف: :error',
        'upload_failed' => 'فشل رفع الصورة: :error',
    ],
    'validation' => [
        'parent_self' => 'لا يمكن أن يكون التصنيف أبًا لنفسه.',
    ],
];
