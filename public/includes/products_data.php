<?php
// Centralized list of services/products with metadata
// Each item: slug (unique), title, description, image (relative to /assets/img)

$products = [
    [
        'slug' => 'custom-trip-design',
        'title' => 'Custom Trip Design',
        'description' => 'Personalized itinerary planning with curated route options and day-by-day schedules tailored to your interests and budget.',
        'image' => '/assets/img/custom-trip-design.svg',
    ],
    [
        'slug' => 'booking-and-logistics',
        'title' => 'Booking & Logistics',
        'description' => 'End-to-end reservations and ticketing for flights, trains, hotels, tours, transfers, and travel insurance.',
        'image' => '/assets/img/booking-and-logistics.svg',
    ],
    [
        'slug' => 'corporate-group-travel',
        'title' => 'Corporate & Group Travel',
        'description' => 'Retreats, incentives, and conferences with agenda design, vendor coordination, and on-site support.',
        'image' => '/assets/img/corporate-group-travel.svg',
    ],
    [
        'slug' => 'concierge-services',
        'title' => 'Concierge Services',
        'description' => 'Restaurant bookings, local guides, event tickets, and VIP experiences with priority access when available.',
        'image' => '/assets/img/concierge-services.svg',
    ],
    [
        'slug' => 'on-trip-support',
        'title' => 'On-Trip Support',
        'description' => '24/7 assistance for changes, issues, and re-routing to minimize disruption and keep your trip on track.',
        'image' => '/assets/img/on-trip-support.svg',
    ],
    [
        'slug' => 'travel-advisory',
        'title' => 'Travel Advisory',
        'description' => 'Destination research, seasonality guidance, budgeting, and risk considerations to inform your decisions.',
        'image' => '/assets/img/travel-advisory.svg',
    ],
    [
        'slug' => 'family-travel-planning',
        'title' => 'Family Travel Planning',
        'description' => 'Kid-friendly itineraries, flexible pacing, and accommodations that make family travel smooth and fun.',
        'image' => '/assets/img/family-travel-planning.svg',
    ],
    [
        'slug' => 'adventure-expeditions',
        'title' => 'Adventure Expeditions',
        'description' => 'Trekking, diving, safaris, and multi-sport adventures with vetted local outfitters and safety-first planning.',
        'image' => '/assets/img/adventure-expeditions.svg',
    ],
    [
        'slug' => 'luxury-getaways',
        'title' => 'Luxury Getaways',
        'description' => 'Boutique stays, private transfers, and exclusive experiences for a seamless, elevated escape.',
        'image' => '/assets/img/luxury-getaways.svg',
    ],
    [
        'slug' => 'eco-sustainable-travel',
        'title' => 'Eco & Sustainable Travel',
        'description' => 'Low-impact itineraries, conservation-focused partners, and carbon-aware choices for responsible travel.',
        'image' => '/assets/img/eco-sustainable-travel.svg',
    ],
];

// Helper: map slug to product for quick lookup
function get_product_by_slug($slug) {
    global $products;
    foreach ($products as $product) {
        if ($product['slug'] === $slug) {
            return $product;
        }
    }
    return null;
}


