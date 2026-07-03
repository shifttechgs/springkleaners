<?php

return [

    // Days a Pending/Quoted booking holds its calendar slot before it auto-expires and frees the slot up.
    'quote_hold_days' => 5,

    'list' => [

        'deep-cleaning' => [
            'slug' => 'deep-cleaning',
            'name' => 'Deep Cleaning',
            'tagline' => 'A thorough top-to-bottom clean of every surface, corner and room.',
            'icon' => 'deep',
            'base_price' => 1200,
            'included_bedrooms' => 2,
            'included_bathrooms' => 1,
            'bedroom_price' => 100,
            'bathroom_price' => 100,
            'extra_room_price' => 80,
            'service_fee' => 30,
            'avg_hours' => 4,
            'unit_label' => 'visit',
        ],

        'end-of-tenancy' => [
            'slug' => 'end-of-tenancy',
            'name' => 'End-of-Tenancy Cleaning',
            'tagline' => 'Move-in/move-out cleaning to landlord and letting-agent standard.',
            'icon' => 'eot',
            'base_price' => 1200,
            'included_bedrooms' => 2,
            'included_bathrooms' => 1,
            'bedroom_price' => 120,
            'bathroom_price' => 100,
            'extra_room_price' => 90,
            'service_fee' => 30,
            'avg_hours' => 5,
            'unit_label' => 'property',
        ],

        'post-construction' => [
            'slug' => 'post-construction',
            'name' => 'Post Construction Cleaning',
            'tagline' => 'We remove what the builders left behind, to a move-in ready standard.',
            'icon' => 'construction',
            'base_price' => 1800,
            'included_bedrooms' => 2,
            'included_bathrooms' => 1,
            'bedroom_price' => 150,
            'bathroom_price' => 120,
            'extra_room_price' => 100,
            'service_fee' => 50,
            'avg_hours' => 6,
            'unit_label' => 'project',
        ],

    ],

    'addons' => [
        ['key' => 'windows', 'label' => 'Interior windows', 'price' => 200, 'desc' => 'All interior glass, frames and sills.'],
        ['key' => 'balcony', 'label' => 'Balcony / patio', 'price' => 150, 'desc' => 'Sweep, mop and tidy outdoor areas.'],
        ['key' => 'walls', 'label' => 'Wall mark removal', 'price' => 150, 'desc' => 'Spot-clean scuffs in main rooms.'],
        ['key' => 'garage', 'label' => 'Garage cleaning', 'price' => 200, 'desc' => 'Sweep out dust, leaves and clutter.'],
    ],

];
