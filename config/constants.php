<?php

return [
    'industryAryList' => [
        'ecommerce'     => 'E-commerce',
        'education'     => 'Education',
        // 'healthcare'    => 'Healthcare',
        // 'finance'       => 'Finance',
        // 'manufacturing' => 'Manufacturing',
        // 'real_estate'   => 'Real Estate',
        // 'hospitality'   => 'Hospitality',
        // 'food_and_beverage' => 'Food & Beverage',
        // 'transportation_and_logistics' => 'Transportation & Logistics',
        // 'media_and_entertainment' => 'Media & Entertainment',
        // 'retail'        => 'Retail',
        // 'legal_services' => 'Legal Services',
        // 'marketing_and_advertising' => 'Marketing & Advertising',
        // 'agriculture' => 'Agriculture',
        // 'non_profit'    => 'Non-Profit / NGO',
        // 'construction'  => 'Construction',
        // 'Energy_and_Utilities' => 'Energy & Utilities',
        // 'telecommunications' => 'Telecommunications',
        // 'Human Resources' => 'Human Resources',
        // 'automotive'    => 'Automotive',
        // 'fashion_apparel' => 'Fashion & Apparel',
        // 'beauty_wellness' => 'Beauty & Wellness',
        // 'sports_recreation' => 'Sports & Recreation',
        // 'government_services' => 'Government Services',
        // 'travel_tourism' => 'Travel & Tourism',
        // 'logistics_supply_chain' => 'Logistics & Supply Chain',
        // 'pharmaceuticals' => 'Pharmaceuticals',
        // 'construction_real_estate' => 'Construction & Real Estate',
        // 'insurance'     => 'Insurance',
        // 'media_entertainment' => 'Media & Entertainment',
        // 'education_training' => 'Education & Training',
        // 'information_technology' => 'Information Technology',
        // 'consulting_professional_services' => 'Consulting & Professional Services',
        // 'research_development' => 'Research & Development',
        // 'technology'    => 'Technology',
    ],

    'industry_icons' => [
        'ecommerce'     => 'fas fa-shopping-cart',
        'education'     => 'fas fa-graduation-cap',
        // 'healthcare'    => 'fas fa-heartbeat',
        // 'finance'       => 'fas fa-chart-line',
        // 'manufacturing' => 'fas fa-industry',
        // 'real_estate'   => 'fas fa-building',
        // 'hospitality'   => 'fas fa-hotel',
        // 'food_and_beverage' => 'fas fa-utensils',
        // 'transportation_and_logistics' => 'fas fa-truck',
        // 'media_and_entertainment' => 'fas fa-film',
        // 'retail'        => 'fas fa-store',
        // 'legal_services' => 'fas fa-balance-scale',
        // 'marketing_and_advertising' => 'fas fa-bullhorn',
        // 'agriculture' => 'fas fa-seedling',
        // 'non_profit'    => 'fas fa-hands-helping',
        // 'construction'  => 'fas fa-hard-hat',
        // 'Energy_and_Utilities' => 'fas fa-bolt',
        // 'telecommunications' => 'fas fa-phone',
        // 'Human Resources' => 'fas fa-users',
        // 'automotive'    => 'fas fa-car',
        // 'fashion_apparel' => 'fas fa-tshirt',
        // 'beauty_wellness' => 'fas fa-spa',
        // 'sports_recreation' => 'fas fa-futbol',
        // 'government_services' => 'fas fa-landmark',
        // 'travel_tourism' => 'fas fa-plane',
        // 'logistics_supply_chain' => 'fas fa-warehouse',
        // 'pharmaceuticals' => 'fas fa-pills',
        // 'construction_real_estate' => 'fas fa-hammer',
        // 'insurance'     => 'fas fa-shield-alt',
        // 'media_entertainment' => 'fas fa-tv',
        // 'education_training' => 'fas fa-chalkboard-teacher',
        // 'information_technology' => 'fas fa-laptop-code',
        // 'consulting_professional_services' => 'fas fa-briefcase',
        // 'research_development' => 'fas fa-microscope',
        // 'technology'    => 'fas fa-microchip',
    ],

    'auto_install_packages' => [
        'admin/admin_auth',
        'admin/settings',
    ],

    'package_display_names' => [
        'admin/admin_auth' => 'Admin Authentication',
        'admin/admin_role_permissions'    => 'Admin Role & Permission Manager',
        'admin/users'     => 'User Manager',
        'admin/pages'     => 'CMS Pages Manager',
        'admin/emails'    => 'Email Template Manager',
        'admin/banners'   => 'Banner Manager',
        'admin/faqs'      => 'Faq Manager',
        'admin/enquiries' => 'Enquiry Manager',
        'admin/brands'    => 'Brand Manager',
        'admin/tags'      => 'Tag Manager',
        'admin/categories' => 'Category Manager',
        'admin/ratings'   => 'Rating Manager',
        'admin/banners'   => 'Banner Manager',
        'admin/faqs'      => 'Faq Manager',
        'admin/enquiries'  => 'Enquiry Manager',
        'admin/products'  => 'Product Manager',
        'admin/shipping_charges' => 'Shipping Charges Manager',
        'admin/coupons' => 'Coupon Manager',
        'admin/courses' => 'Course Manager',
        'admin/certificates' => 'Certificate Manager',
        'admin/wishlists' => 'Wishlist Manager',
        'admin/quizzes' => 'Quiz Manager',
        'admin/settings'  => 'Setting Manager',
    ],

    'common_packages' => [
        'admin/admin_role_permissions',
        'admin/users',
        'admin/pages',
        'admin/emails',
        'admin/banners',
        'admin/faqs',
        'admin/enquiries',
    ],

    'industry_packages' => [
        'ecommerce' => [
            'admin/brands',
            'admin/tags',
            'admin/categories',
            'admin/ratings',
            'admin/products',
            'admin/wishlists',
            'admin/shipping_charges',
            'admin/coupons',
        ],
        'education' => [
            'admin/tags',
            'admin/categories',
            'admin/ratings',
            'admin/coupons',
            'admin/courses',
            'admin/wishlists',
            'admin/certificates',
            'admin/quizzes',
        ]
    ],

    'package_info' => [
        'admin/admin_auth' => [
            'description' => 'Core authentication system for admin panel with user management and security features.',
        ],

        'admin/emails' => [
            'description' => 'Manage and configure email templates used across the system for various notifications and communications.',
        ],
        'admin/faqs' => [
            'description' => 'Add, edit, or remove frequently asked questions to help users quickly find answers to common queries.',
        ],
        'admin/pages' => [
            'description' => 'Create and manage static content pages (e.g., About Us, Terms & Conditions) to keep the site content updated and organized.',
        ],
        'admin/settings' => [
            'description' => 'Configure general system settings, preferences, and options to customize the application’s behavior and appearance.',
        ],
        'admin/banners' => [
            'description' => 'Manage promotional banners and visual advertisements displayed throughout the site to enhance marketing and user engagement.',
        ],
        'admin/users' => [
            'description' => 'Manage user accounts, roles, and permissions to control access and maintain user information within the system.',
        ],
        'admin/categories' => [
            'description' => 'Create, edit, and organize categories to structure content or products for easier navigation and management.',
        ],
        'admin/admins' => [
            'description' => 'Manage administrator accounts, assign roles, and control access to backend features and settings.',
        ],
        'admin/user_roles' => [
            'description' => 'Define and manage user roles to organize responsibilities, and access levels across the application.',
        ],
        'admin/admin_role_permissions' => [
            'description' => 'Manage and configure role-based permissions for administrators within the system.',
        ],
        'admin/enquiries' => [
            'description' => 'Manage and configure user enquiries in the system.',
        ],
        'admin/brands' => [
            'description' => 'Manage product brands by adding, editing, or organizing brand information to enhance product categorization and recognition.',
        ],
        'admin/tags' => [
            'description' => 'Manage tags to categorize and organize content or products, allowing for better filtering and search capabilities.',
        ],
        'admin/ratings' => [
            'description' => 'Manage user ratings and reviews for products or services, allowing users to provide feedback and ratings.',
        ],
        'admin/products' => [
            'description' => 'Manage products by adding, editing, or organizing product information to enhance product categorization and recognition.',
        ],
        'admin/coupons' => [
            'description' => 'Manage discount coupons to provide promotional offers and incentives to users.',
        ],
        'admin/courses' => [
            'description' => 'Manage courses by adding, editing, or organizing course information to enhance educational offerings.',
        ],
        'admin/certificates' => [
            'description' => 'Manage certificates by adding, editing, or organizing certificate information to enhance educational offerings.',
        ],
        'admin/wishlists' => [
            'description' => 'To analyze customer interest trends or enable users to save items they may buy later.',
        ],
        'admin/quizzes' => [
            'description' => 'Manage quizzes by adding, editing, or organizing quiz information to enhance educational offerings.',
        ],
    ]

];
