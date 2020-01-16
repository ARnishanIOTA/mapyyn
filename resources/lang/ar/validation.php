<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول :attribute',
    'active_url' => ':attribute لا يُمثّل رابطًا صحيحًا',
    'after' => 'يجب على :attribute أن يكون تاريخًا لاحقًا للتاريخ :date.',
    'after_or_equal' => ':attribute يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
    'alpha' => 'يجب أن لا يحتوي :attribute سوى على حروف',
    'alpha_dash' => 'يجب أن لا يحتوي :attribute على حروف، أرقام ومطّات.',
    'alpha_num' => 'يجب أن يحتوي :attribute على حروفٍ وأرقامٍ فقط',
    'array' => 'يجب أن يكون :attribute ًمصفوفة',
    'before' => 'يجب على :attribute أن يكون تاريخًا سابقًا للتاريخ :date.',
    'before_or_equal' => ':attribute يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date',
    'between' => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون عدد حروف النّص :attribute بين :min و :max',
        'array' => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max',
    ],
    'boolean' => 'يجب أن تكون قيمة :attribute  صحيحة ',
    'confirmed' => 'حقل التأكيد غير مُطابق للحقل :attribute',
    'date' => ':attribute ليس تاريخًا صحيحًا',
    'date_format' => 'لا يتوافق :attribute مع الشكل :format.',
    'different' => 'يجب أن يكون الحقلان :attribute و :other مُختلفان',
    'digits' => 'يجب أن يحتوي :attribute على :digits رقمًا/أرقام',
    'digits_between' => 'يجب أن يحتوي :attribute بين :min و :max رقمًا/أرقام ',
    'dimensions' => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'للحقل :attribute قيمة مُكرّرة.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح البُنية',
    'exists' => ' :attribute غير موجودة',
    'file' => 'الـ :attribute يجب أن يكون ملفا.',
    'filled' => ':attribute إجباري',
    'gt'                   => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file'    => 'The :attribute must be greater than :value kilobytes.',
        'string'  => 'The :attribute must be greater than :value characters.',
        'array'   => 'The :attribute must have more than :value items.',
    ],
    'gte'                  => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file'    => 'The :attribute must be greater than or equal :value kilobytes.',
        'string'  => 'The :attribute must be greater than or equal :value characters.',
        'array'   => 'The :attribute must have :value items or more.',
    ],
    'image' => 'يجب أن يكون :attribute صورةً',
    'in' => ':attribute لاغٍ',
    'in_array' => ':attribute غير موجود في :other.',
    'integer' => 'يجب أن يكون :attribute عددًا صحيحًا',
    'ip' => 'يجب أن يكون :attribute عنوان IP صحيحًا',
    'ipv4' => 'يجب أن يكون :attribute عنوان IPv4 صحيحًا.',
    'ipv6' => 'يجب أن يكون :attribute عنوان IPv6 صحيحًا.',
    'json' => 'يجب أن يكون :attribute نصآ من نوع JSON.',
    
    'lt'                   => [
        'numeric' => 'The :attribute must be less than :value.',
        'file'    => 'The :attribute must be less than :value kilobytes.',
        'string'  => 'The :attribute must be less than :value characters.',
        'array'   => 'The :attribute must have less than :value items.',
    ],
    'lte'                  => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file'    => 'The :attribute must be less than or equal :value kilobytes.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
        'array'   => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أصغر لـ :max.',
        'file' => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت',
        'string' => 'يجب أن لا يتجاوز طول النّص :attribute :max حروفٍ/حرفًا',
        'array' => 'يجب أن لا يحتوي :attribute على أكثر من :max عناصر/عنصر.',
    ],
    'mimes' => 'يجب أن يكون ملفًا من نوع : :values.',
    'mimetypes' => 'يجب أن يكون ملفًا من نوع : :values.',
    'min' => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أكبر لـ :min.',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت',
        'string' => 'يجب أن يكون طول النص :attribute على الأقل :min حروفٍ/حرفًا',
        'array' => 'يجب أن يحتوي :attribute على الأقل على :min عُنصرًا/عناصر',
    ],
    'not_regex'            => 'The :attribute format is invalid.',
    'not_in' => ':attribute لاغٍ',
    'numeric' => 'يجب على :attribute أن يكون رقمًا',
    'present' => 'يجب تقديم :attribute',
    'regex' => 'صيغة :attribute .غير صحيحة',
    'required' => ':attribute مطلوب.',
    'required_if' => ':attribute مطلوب في حال ما إذا كان :other يساوي :value.',
    'required_unless' => ':attribute مطلوب في حال ما لم يكن :other يساوي :values.',
    'required_with' => ':attribute مطلوب إذا توفّر :values.',
    'required_with_all' => ':attribute مطلوب إذا توفّر :values.',
    'required_without' => ':attribute مطلوب إذا لم يتوفّر :values.',
    'required_without_all' => ':attribute مطلوب إذا لم يتوفّر :values.',
    'same' => 'يجب أن يتطابق :attribute مع :other',
    'size' => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية لـ :size',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت',
        'string' => 'يجب أن يحتوي النص :attribute على :size حروفٍ/حرفًا بالظبط',
        'array' => 'يجب أن يحتوي :attribute على :size عنصرٍ/عناصر بالظبط',
    ],
    'string' => 'يجب أن يكون :attribute نصآ.',
    'timezone' => 'يجب أن يكون :attribute نطاقًا زمنيًا صحيحًا',
    'unique' => ':attribute مُستخدم من قبل',
    'uploaded' => 'فشل في تحميل الـ :attribute',
    'url' => 'صيغة الرابط :attribute غير صحيحة',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'password' => [
            'regex' => 'كلمة المرور يجب ان تحتوى على حروف كبيرة وصغيرة وارقام'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'الاسم',
        'username' => 'اسم المُستخدم',
        'email' => 'البريد الالكتروني',
        'first_name' => 'الاسم الأول',
        'last_name' => 'اسم العائلة',
        'password' => 'كلمة السر',
        'password_confirmation' => 'تأكيد كلمة السر',
        'city' => 'المدينة',
        'country' => 'الدولة',
        'address' => 'عنوان السكن',
        'phone' => 'الهاتف',
        'mobile' => 'الجوال',
        'age' => 'العمر',
        'sex' => 'الجنس',
        'gender' => 'النوع',
        'day' => 'اليوم',
        'month' => 'الشهر',
        'year' => 'السنة',
        'hour' => 'ساعة',
        'minute' => 'دقيقة',
        'second' => 'ثانية',
        'title' => 'العنوان',
        'content' => 'المُحتوى',
        'description' => 'الوصف',
        'excerpt' => 'المُلخص',
        'date' => 'التاريخ',
        'time' => 'الوقت',
        'available' => 'مُتاح',
        'size' => 'الحجم',
        'status' => 'الحالة',
        'facebook'  => 'فسبوك',
        'twitter' => 'تويتر',
        'whatsapp' => 'واتساب',
        'skype' => 'سكايب',
        'sub_name' => 'الاسم الفرعي',
        'author' => 'المؤلف',
        'lang' => 'اللغة',
        'version' => 'الاصدار',
        'pages' => 'عدد الصفحات',
        'price' => 'السعر',
        'special' => 'التمييز',
        'sub_category_id' => 'القسم الفرعي',
        'image' => 'الصورة',
        'message' => 'الرسالة',
        'expire_at' => 'تاريخ الانتهاء',
        'num_of_use' => 'عد مرات الاستخدام',
        'discount' => 'نسبة الخصم',
        'key' => 'مفتاح الدولة',
        'country_id' => 'الدولة',
        'category_id' => 'القسم',
        'token' => 'رمز التفعيل ',
        'link' => 'الرابط',
        'tags' => 'الكلمات الدلالية',
        'subCategories' => 'الموضوعات',
        'teacher_id' => 'المدرب',
        'number' => 'عدد الاسئله',
        'type' => 'القسم',
        'body' => 'النص',
        'start_date' => 'تاريخ البدء',
        'end_date' => 'تاريخ الإنتهاء',
        'image' => 'الصورة',
        'status' => 'الحالة',
        'name_ar' => 'الاسم بالعربي',
        'name_en' => 'الاسم بالإنجليزي',
        'description_ar' => 'الوصف بالعربي',
        'description_en' => 'الوصف بالإنجليزي',
        'qty' => 'الكمية',
        'images' => 'الصور',
        'title_ar' => 'العنوان بالعربي',
        'title_en' => 'العنوان بالانجليزي',
        'message_ar' => 'الرسالة بالعربي',
        'message_en' => 'الرسالة بالانجليزي',
        'type' => 'النوع',
        'fb' => 'فيسبوك',
        'tw' => 'تويتر',
        'whatsapp' => 'واتساب',
        'delivery_price' => 'سعر التوصيل',
        'logo' => 'اللوجو',
        'about_ar' => 'عن الموقع بالعربي',
        'about_en' => 'عن الموقع بالانجليزي',
        'terms_ar' => 'شروط الاستخدام بالعربي',
        'terms_en' => 'شروط الاستخدام بالانجليزي',
        'subject' => 'العنوان',
        'message' => 'الرسالة',
        'reason' => 'السبب',
        'roles' => 'الوظائف',
        'permission_id' => 'الصلاحيات',
        'user_type' => 'نوع المستخدم' ,
        'persons' => 'عدد الأشخاص',
        'transport' => 'التنقل',
        'days' => 'عدد الايام',
        'hotel_level' => 'مستوى الفندق',
        'from_country' => 'من دولة',
        'from_city' => 'من مدينة',
        'to_country' => 'الى دولة',
        'to_city' => 'الى مدينة',
        'adult' => 'بالغين',
        'reply_time' => 'زمن الرد',
        'activation_code' => 'كود التفعيل',
        'city_id' => 'المدينة',
        'categories' => 'التصنيفات',
        'interests' => 'الاهتمامات',
        'go_date' => 'تاريخ الذهاب',
        'back_date' => 'تاريخ العودة',
        'trip_stop' => 'رحلات بدون توقف',
        'help' => 'المساعدة',
        'change_date' => 'امكانية تغيير التواريخ',
        'sar_dollar' => 'سعر الريال بالنسبة للدولار',
        'passport_number'  => 'رقم جواز السفر',
        'passport_end_date'  => 'تاريخ انتهاء جواز السفر',
        'passport_country' => 'بلد اصدار جواز السفر',
        'nationality'     => 'الجنسية',
        'birthdate'       => 'تاريخ الميلاد',
        "passengers.*.name"  => 'الاسم', 
        "passengers.*.birthdate"  => 'تاريخ الميلاد', 
        "passengers.*.passport_country"  => 'بلد جواز السفر', 
        "passengers.*.passport_number"  => 'رقم جواز السفر', 
        "passengers.*.nationality"  => 'الجنسية',
        'category_id' => 'التصنيف'
    ],

];
