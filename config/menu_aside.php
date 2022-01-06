<?php
// Aside menu
return [

    'items' => [
        // Dashboard
        [
            'title' => 'Güncel Durum',
            'root' => true,
            'icon' => 'media/svg/icons/Design/Layers.svg', // or can be 'flaticon-home' or any flaticon-*
            'page' => '/',
            'sef' => 'dashboard',
            'new-tab' => false,
        ],

        // Custom
        [
            'section' => 'Kayıtlar',
            'sef' => 'records'
        ],
        [
            'title' => 'Müşteriler',
            'icon' => 'media/svg/icons/General/User.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'customers',
            'sef' => 'customers',
        ],
        [
            'title' => 'Projeler',
            'icon' => 'media/svg/icons/Clothes/Briefcase.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'projects',
            'sef' => 'projects',
        ],
        // Custom
        [
            'section' => 'İŞ EMRİ',
            'sef' => 'workflow'
        ],
        [
            'title' => 'Keşif',
            'icon' => 'media/svg/icons/Design/Arrows.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'exploration',
            'sef' => 'exploration',
        ],
        [
            'title' => 'Üretim',
            'icon' => 'media/svg/icons/Home/Library.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'production',
            'sef' => 'production',
        ],
        [
            'title' => 'Montaj',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'assembly',
            'sef' => 'assembly',
        ],
        [
            'title' => 'Baskı',
            'icon' => 'media/svg/icons/Design/Bucket.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'printing',
            'sef' => 'printing',
        ],
        [
            'section' => 'Süreç',
            'sef' => 'surec',
        ],
        [
            'title' => 'Brief',
            'root' => true,
            'icon' => 'media/svg/icons/Communication/Mail-notification.svg', 
            'page' => 'briefs',
            'sef' => 'briefs',
            'new-tab' => false,
        ],
        [
            'title' => 'Teklif',
            'root' => true,
            'icon' => 'media/svg/icons/Shopping/Calculator.svg', 
            'page' => 'offers',
            'sef' => 'offers',
            'new-tab' => false,
        ],
        [
            'title' => 'Sözleşme',
            'root' => true,
            'icon' => 'media/svg/icons/Shopping/Wallet3.svg', 
            'page' => 'contracts',
            'sef' => 'contracts',
            'new-tab' => false,
        ],
        [
            'title' => 'Fatura',
            'root' => true,
            'icon' => 'media/svg/icons/Text/Align-auto.svg', 
            'page' => 'bills',
            'sef' => 'bills',
            'new-tab' => false,
        ],
        // Custom
        [
            'section' => 'Satın Alma',
            'sef' => 'satin_alma'
        ],
        [
            'title' => 'Tedarikçiler',
            'icon' => 'media/svg/icons/General/User.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'suppliers',
            'sef' => 'supplier',
        ],
        [
            'title' => 'Satın Alma',
            'icon' => 'media/svg/icons/Shopping/Wallet3.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'purchases',
            'sef' => 'purchase',
        ],
        [
            'title' => 'Tedarikçi Faturaları',
            'icon' => 'media/svg/icons/Shopping/Money.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'expenses',
            'sef' => 'expense',
        ],
        [
            'title' => 'Ürünler',
            'icon' => 'media/svg/icons/Shopping/Box3.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'products',
            'sef' => 'product',
        ],
        [
            'section' => 'Yönetim',
            'sef' => 'surec',
        ],
        [
            'title' => 'Masraf Fişleri',
            'root' => true,
            'icon' => 'media/svg/icons/Shopping/Sale1.svg', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'costs',
            'new-tab' => false,
            'sef' => 'cost'
        ],
        [
            'section' => 'Çekler',
            'sef' => 'cheque',
        ],
        [
            'title' => 'Verilen Çekler',
            'root' => true,
            'icon' => 'media/svg/icons/Communication/Share.svg', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'cheque/send',
            'new-tab' => false,
            'sef' => 'cheque'
        ],
        [
            'title' => 'Alınan Çekler',
            'root' => true,
            'icon' => 'media/svg/icons/Communication/Reply-all.svg', // or can be 'flaticon-home' or any flaticon-*
            'page' => 'cheque/received',
            'new-tab' => false,
            'sef' => 'cheque'
        ],

        [
            'section' => 'Genel Bilgiler',
            'sef' => 'firm'
        ],
        [
            'title' => 'Kullanıcılar',
            'icon' => 'media/svg/icons/Communication/Add-user.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'users',
            'sef' => 'users',
        ],
        [
            'title' => 'Araçlar',
            'icon' => 'media/svg/icons/Devices/Router2.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'vehicles',
            'sef' => 'vehicles',
        ],
        [
            'title' => 'Baskı Özellikleri',
            'icon' => 'media/svg/icons/Communication/Dial-numbers.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => 'printing-meta',
            'sef' => 'printing_meta',
        ],
    ]

];
