<?php

return [
    'industryAryList' => [
        'healthcare'    => 'Healthcare',
        'finance'       => 'Finance',
        'education'     => 'Education',
        'retail'        => 'Retail',
        'manufacturing' => 'Manufacturing',
        'technology'    => 'Technology',
        'ecommerce'     => 'Ecommerce',
    ],

    'package_display_names' => [
        'admin/admin_role_permissions'    => 'Role Permission Manager',
        'admin/users'     => 'User Manager',
        'admin/categories' => 'Category Manager',
        'admin/banners'   => 'Banner Manager',
        'admin/emails'    => 'Email Template Manager',
        'admin/faqs'      => 'Faq Manager',
        'admin/pages'     => 'CMS Pages Manager',
        'admin/settings'  => 'Setting Manager',
    ],

    'industry_packages' => [
        'education' => [
            'admin/categories',
            'admin/faqs',
            'admin/pages',
            // 'admin/settings',
        ],
        'ecommerce' => [
            // 'admin/admins',
            'admin/banners',
            'admin/categories',
            // 'admin/emails',
            'admin/faqs',
            'admin/pages',
            // 'admin/settings',
            'admin/users',
        ],
        'finance' => [
            'admin/categories',
            // 'admin/emails',
            'admin/faqs',
            'admin/users',
        ],
        'healthcare' => [
            'admin/admin_role_permissions',
            'admin/users',
            'admin/categories',
            'admin/banners',
            // 'admin/emails',
            'admin/pages',
            // 'admin/settings',
        ],
        'manufacturing' => [
            // 'admin/admins',
            // 'admin/emails',
            'admin/faqs',
            'admin/pages',
        ],
        'retail' => [
            'admin/banners',
            'admin/faqs',
            'admin/pages',
            'admin/users',
        ],
        'technology' => [
            'admin/categories',
            'admin/faqs',
            'admin/pages',
            // 'admin/settings',
        ],
    ],
    'package_info' => [
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
        // 'admin/user_roles' => [
        //     'description' => 'Define and manage user roles to organize responsibilities, and access levels across the application.',
        // ],
        'admin/admin_role_permissions' => [
            'description' => 'Manage and configure role-based permissions for administrators within the system.',
        ],
    ],

];
