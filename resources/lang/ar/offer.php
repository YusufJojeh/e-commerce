<?php

return [
    'titles' => [
        'create' => 'إضافة عرض جديد',
        'edit' => 'تعديل العرض',
        'current_banner' => 'البانر الحالي',
    ],
    'fields' => [
        'title' => 'العنوان',
        'description' => 'الوصف',
        'type' => 'النوع',
        'value' => 'القيمة',
        'starts_at' => 'يبدأ في',
        'ends_at' => 'ينتهي في',
        'is_active' => 'مفعل',
        'banner' => 'البانر',
    ],
    'help' => [
        'type' => 'إذا كان الشحن مجاني، يتم تجاهل القيمة.',
        'value' => 'للنسبة المئوية: 1-100. للمبلغ الثابت: >= 0',
        'banner' => 'قم برفع بانر (JPG/PNG/WebP). الحجم الأقصى 5MB',
        'banner_current_prefix' => 'الحالي: ',
    ],
    'types' => [
        'percent' => 'نسبة مئوية (%)',
        'fixed' => 'مبلغ ثابت',
        'free_shipping' => 'شحن مجاني',
    ],
    'actions' => [
        'save' => 'حفظ',
        'remove' => 'حذف',
        'confirm_remove' => 'حذف هذا العرض؟',
    ],
    'toast' => [
        'saved' => 'تم حفظ العرض بنجاح',
        'deleted' => 'تم حذف العرض بنجاح',
        'delete_failed' => 'فشل في حذف العرض: :error',
        'upload_failed' => 'فشل رفع البانر: :error',
    ],
];
