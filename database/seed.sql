-- OM Trip and Travels - Seed Data
-- USE `omtrip_db`; (leave commented for Hostinger phpMyAdmin)
SET NAMES utf8mb4;

-- Super Admin account (Password: Admin@123456)
INSERT INTO `admins` (`name`, `email`, `password`, `role`, `is_active`) VALUES
('Super Admin', 'admin@omtripandtravels.com', '$2y$12$BSXWpKNJ0zYys74uNbVFpOmaZN/MruVQ//im7elYD3HYVj7GJifDu', 'super_admin', 1)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Default settings (all keys)
INSERT INTO `settings` (`key`, `value`, `type`, `group`, `label`) VALUES
('business_name', 'OM Trip and Travels', 'text', 'general', 'Business Name'),
('tagline', 'Urbania & Tempo Traveller Specialists in Indore', 'text', 'general', 'Tagline'),
('phone', '+91 98260 12345', 'text', 'general', 'Phone Number'),
('whatsapp_number', '919826012345', 'text', 'general', 'WhatsApp Number (without +)'),
('email', 'info@omtripandtravels.com', 'text', 'general', 'Email Address'),
('address', '123, Vijay Nagar, Indore, Madhya Pradesh - 452010', 'textarea', 'general', 'Full Address'),
('city', 'Indore', 'text', 'general', 'City'),
('state', 'Madhya Pradesh', 'text', 'general', 'State'),
('pincode', '452010', 'text', 'general', 'Pincode'),
('google_maps_embed', '', 'textarea', 'general', 'Google Maps Embed Code'),
('working_hours', 'Mon–Sun: 24 Hours, 7 Days a Week', 'text', 'general', 'Working Hours'),
('logo', '/assets/images/logo.png', 'image', 'general', 'Logo'),
('favicon', '/assets/images/favicon.png', 'image', 'general', 'Favicon'),
('footer_about', 'OM Trip and Travels is Indore\'s trusted name for group travel. We specialize in Urbania and Tempo Traveller rentals for pilgrimages, corporate trips, and leisure tours across Madhya Pradesh and beyond.', 'textarea', 'general', 'Footer About Text'),
('facebook_url', '', 'text', 'social', 'Facebook URL'),
('instagram_url', '', 'text', 'social', 'Instagram URL'),
('youtube_url', '', 'text', 'social', 'YouTube URL'),
('twitter_url', '', 'text', 'social', 'Twitter/X URL'),
('wa_message_vehicle', 'Hello, I am interested in booking the {vehicle_name}. Please share availability and pricing. Page: {page_url}', 'textarea', 'whatsapp', 'WhatsApp Message - Vehicle'),
('wa_message_package', 'Hello, I want to enquire about the {package_name} tour package. Page: {page_url}', 'textarea', 'whatsapp', 'WhatsApp Message - Package'),
('wa_message_general', 'Hello OM Trip and Travels, I would like to enquire about your services. Page: {page_url}', 'textarea', 'whatsapp', 'WhatsApp Message - General'),
('analytics_code', '', 'textarea', 'scripts', 'Google Analytics / GTM Code'),
('header_scripts', '', 'textarea', 'scripts', 'Custom Header Scripts'),
('footer_scripts', '', 'textarea', 'scripts', 'Custom Footer Scripts'),
('meta_home_title', 'OM Trip and Travels | Urbania & Tempo Traveller Rental in Indore', 'text', 'seo', 'Home Page Meta Title'),
('meta_home_description', 'Book Urbania, Tempo Traveller, Innova, Fortuner on rent in Indore. Best group travel packages to Ujjain, Omkareshwar, Maheshwar, Pachmarhi, Shirdi & more.', 'textarea', 'seo', 'Home Page Meta Description'),
('turnstile_site_key', '', 'text', 'security', 'Cloudflare Turnstile Site Key'),
('turnstile_secret_key', '', 'text', 'security', 'Cloudflare Turnstile Secret Key');

-- Vehicle Categories
INSERT INTO `vehicle_categories` (`name`, `slug`, `description`, `icon`, `sort_order`) VALUES
('Urbania', 'urbania', 'Force Urbania luxury vans — perfect for 12 to 17 passengers with air conditioning and plush seating.', 'bi-minecart-loaded', 1),
('Tempo Traveller', 'tempo-traveller', 'Spacious Tempo Travellers available in 12, 14, and 17 seater configurations for comfortable group travel.', 'bi-truck-front', 2),
('SUV', 'suv', 'Premium SUVs including Toyota Fortuner and Innova for comfortable long-distance travel.', 'bi-car-front-fill', 3),
('Sedan', 'sedan', 'Comfortable sedans for airport transfers, local sightseeing, and outstation travel.', 'bi-car-front', 4),
('Others', 'others', 'Other vehicle types including buses and specialty vehicles for large groups.', 'bi-bus-front', 5);

-- Vehicles
INSERT INTO `vehicles` (`category_id`, `name`, `slug`, `short_description`, `seats`, `has_ac`, `features`, `ideal_for`, `starting_price`, `price_note`, `is_featured`, `sort_order`, `meta_title`, `meta_description`) VALUES
(1, 'Force Urbania 12 Seater', 'force-urbania-12-seater', 'Luxury Force Urbania with 12 captain seats, perfect for comfortable group travel from Indore.', 12, 1, '["Reclining Captain Seats","Pushback Seats","LED Lighting","Charging Points","Music System","Curtains","Ample Luggage Space","Cooler"]', '["Pilgrimage Tours","Corporate Outings","Family Trips","Airport Transfers"]', NULL, 'On Request — Call for Best Price', 1, 1, 'Urbania 12 Seater on Rent in Indore | OM Trip and Travels', 'Book Force Urbania 12 Seater on rent in Indore. Best rates for Ujjain, Omkareshwar, Maheshwar, Shirdi & all MP destinations. Call now.'),
(1, 'Force Urbania 17 Seater', 'force-urbania-17-seater', 'Force Urbania 17 seater — Indore''s most popular large van for pilgrimages and group tours.', 17, 1, '["Reclining Captain Seats","Pushback Seats","LED Lighting","Charging Points","Music System","Curtains","Ample Luggage Space","Cooler"]', '["Pilgrimage Tours","Large Group Tours","Corporate Events","Wedding Transport"]', NULL, 'On Request — Call for Best Price', 1, 2, 'Urbania 17 Seater on Rent in Indore | OM Trip and Travels', 'Hire Force Urbania 17 Seater in Indore for group trips. Comfortable, AC, affordable. Call for best price to Ujjain, Shirdi, Dwarka.'),
(2, 'Tempo Traveller 12 Seater', 'tempo-traveller-12-seater', 'AC Tempo Traveller 12 seater — ideal for family trips and small group outings from Indore.', 12, 1, '["Pushback Seats","AC","Music System","TV","Curtains","Luggage Space","Reading Lights"]', '["Family Outings","Pilgrimage","Corporate","Airport Transfers"]', NULL, 'On Request — Call for Best Price', 1, 3, 'Tempo Traveller 12 Seater on Rent in Indore | OM Trip and Travels', 'Book AC Tempo Traveller 12 Seater in Indore. Best price for outstation, pilgrimage, and local tours. Call now.'),
(2, 'Tempo Traveller 14 Seater', 'tempo-traveller-14-seater', 'AC Tempo Traveller 14 seater for medium-sized groups visiting MP pilgrimage destinations.', 14, 1, '["Pushback Seats","AC","Music System","TV","Curtains","Luggage Space","Reading Lights"]', '["Pilgrimage","Group Tours","Corporate Outings","Wedding"]', NULL, 'On Request — Call for Best Price', 0, 4, 'Tempo Traveller 14 Seater on Rent in Indore | OM Trip and Travels', 'Hire Tempo Traveller 14 Seater in Indore for group tours. AC, comfortable, experienced driver. Best rates guaranteed.'),
(2, 'Tempo Traveller 17 Seater', 'tempo-traveller-17-seater', 'Large AC Tempo Traveller 17 seater — Indore''s best choice for big group travel.', 17, 1, '["Pushback Seats","AC","Music System","TV","Curtains","Luggage Space","Reading Lights"]', '["Large Group Tours","Pilgrimage","Corporate Events","School Trips"]', NULL, 'On Request — Call for Best Price', 1, 5, 'Tempo Traveller 17 Seater on Rent in Indore | OM Trip and Travels', 'Book Tempo Traveller 17 Seater on rent in Indore. Best group travel rates for MP and Pan-India tours. Call now.'),
(3, 'Toyota Fortuner', 'toyota-fortuner', 'Premium Toyota Fortuner for luxury outstation trips and airport transfers from Indore.', 7, 1, '["Leather Seats","AC","4WD","Music System","Sunroof","GPS","Charging Points"]', '["Luxury Travel","Airport Transfers","Business Trips","Hill Stations"]', NULL, 'On Request — Call for Best Price', 1, 6, 'Toyota Fortuner on Rent in Indore | OM Trip and Travels', 'Hire Toyota Fortuner on rent in Indore. Premium SUV for outstation trips, airport transfers, corporate travel. Best rates.'),
(3, 'Toyota Innova Crysta', 'toyota-innova-crysta', 'The classic Toyota Innova Crysta — Indore''s most trusted cab for family and corporate travel.', 7, 1, '["Spacious Interior","AC","Music System","GPS","Charging Points"]', '["Family Travel","Airport Transfers","Outstation","Local Sightseeing"]', NULL, 'On Request — Call for Best Price', 1, 7, 'Toyota Innova Crysta on Rent in Indore | OM Trip and Travels', 'Book Toyota Innova Crysta on rent in Indore. Best price for airport, outstation & local travel. 24/7 available.'),
(3, 'Toyota Innova Hycross', 'toyota-innova-hycross', 'Next-gen Toyota Innova Hycross — premium hybrid MPV with class-leading comfort.', 7, 1, '["Hybrid Engine","Panoramic Sunroof","Ventilated Seats","AC","Premium Sound","LED Ambient Lighting","Wireless Charging"]', '["Luxury Travel","Corporate","Airport Transfers","Special Occasions"]', NULL, 'On Request — Call for Best Price', 1, 8, 'Innova Hycross on Rent in Indore | OM Trip and Travels', 'Hire Toyota Innova Hycross on rent in Indore. Premium hybrid MPV for luxury travel. Best rates for airport, outstation trips.');

-- Tour Packages
INSERT INTO `tour_packages` (`name`, `slug`, `short_description`, `description`, `inclusions`, `exclusions`, `duration_days`, `duration_nights`, `route`, `origin`, `destination`, `is_featured`, `sort_order`, `meta_title`, `meta_description`) VALUES
('Indore to Ujjain Darshan', 'indore-to-ujjain', 'One-day Mahakaleshwar Jyotirlinga darshan from Indore. The most popular pilgrimage route in MP.', '<p>Start your spiritual journey from Indore to Ujjain, home of the famous Mahakaleshwar Jyotirlinga temple — one of the 12 sacred Jyotirlingas in India. Our comfortable vehicles will take you on this 55 km journey in style and comfort.</p><p>Ujjain is also home to Harsiddhi Temple, Sandipani Ashram (where Lord Krishna studied), Kal Bhairav Temple, and Ram Ghat.</p>', 'Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking', 'Entry fees\nPuja charges\nFood and accommodation\nPersonal expenses', 1, 0, 'Indore → Ujjain → Indore', 'Indore', 'Ujjain', 1, 1, 'Indore to Ujjain Cab | Mahakaleshwar Darshan Package | OM Trip and Travels', 'Book Indore to Ujjain cab for Mahakaleshwar darshan. One-day tour with Urbania, Tempo Traveller, Innova, Fortuner. Call for best price.'),
('Indore to Omkareshwar Darshan', 'indore-to-omkareshwar', 'Visit the sacred Omkareshwar Jyotirlinga on the banks of Narmada river, 80 km from Indore.', '<p>Omkareshwar is one of the 12 Jyotirlingas of Lord Shiva, located on an island shaped like the Hindu symbol Om. It sits at the confluence of the Narmada and Kaveri rivers, 80 km from Indore.</p><p>The island temple, scenic riverbank ghats, and spiritual atmosphere make this a must-visit pilgrimage for devotees from Indore and across India.</p>', 'Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking', 'Entry fees\nBoat charges\nFood and accommodation\nPersonal expenses', 1, 0, 'Indore → Omkareshwar → Indore', 'Indore', 'Omkareshwar', 1, 2, 'Indore to Omkareshwar Cab | Jyotirlinga Darshan | OM Trip and Travels', 'Book Indore to Omkareshwar taxi for Jyotirlinga darshan. Best rates for Urbania, Tempo Traveller, Innova. Call now.'),
('Maheshwar–Mandu Heritage Tour', 'maheshwar-mandu-tour', 'Explore the royal heritage of Maheshwar and the historic ruins of Mandu in a single day trip from Indore.', '<p>Maheshwar, the erstwhile capital of the Holkar dynasty, sits on the banks of the Narmada. Famous for its Ghats, Ahilya Bai Holkar Fort, and the iconic Maheshwar sarees.</p><p>Mandu (Mandavgadh) is a stunning ruined city with beautiful Mughal-era architecture including Jahaz Mahal, Hindola Mahal, and Rani Roopmati Pavilion — all set amidst nature.</p>', 'Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking', 'Entry fees\nFood and accommodation\nPersonal expenses', 1, 0, 'Indore → Maheshwar → Mandu → Indore', 'Indore', 'Maheshwar & Mandu', 1, 3, 'Maheshwar Mandu Tour from Indore | OM Trip and Travels', 'Book Maheshwar Mandu day trip from Indore. Heritage tour with AC cab, Innova, Urbania, Tempo Traveller. Call for best price.'),
('Indore to Pachmarhi Tour', 'indore-to-pachmarhi', 'Queen of Satpura hills — 2 night Pachmarhi tour from Indore with all major sightseeing spots.', '<p>Pachmarhi, the only hill station in Madhya Pradesh, is known for its scenic beauty, waterfalls, ancient caves, and lush forests in the Satpura Tiger Reserve. Located 220 km from Indore, it''s perfect for a 2-night getaway.</p><p>Key attractions: Bee Falls, Pandava Caves, Jata Shankar, Dhupgarh (highest peak of Satpura), Priyadarshini Point.</p>', 'Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking', 'Hotel accommodation\nEntry fees\nFood\nPersonal expenses', 3, 2, 'Indore → Pachmarhi → Indore', 'Indore', 'Pachmarhi', 1, 4, 'Indore to Pachmarhi Tour Package | OM Trip and Travels', 'Book Indore to Pachmarhi 3 days 2 nights trip. Best package with Tempo Traveller, Urbania, Innova. Call now for group tours.'),
('Indore to Shirdi Tour', 'indore-to-shirdi', 'Sai Baba darshan at Shirdi — popular overnight pilgrimage tour from Indore.', '<p>Shirdi, the abode of Sai Baba, is a popular pilgrimage destination approximately 500 km from Indore. Our comfortable Urbania and Tempo Travellers make overnight group travel easy and affordable.</p><p>Visit the Sai Baba Temple, Dwarkamai Mosque, Chavadi, Lendi Baug, and Gurusthan during your stay.</p>', 'Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking', 'Hotel accommodation\nEntry fees\nFood\nPersonal expenses', 2, 1, 'Indore → Shirdi → Indore', 'Indore', 'Shirdi', 1, 5, 'Indore to Shirdi Tour Package | OM Trip and Travels', 'Book Indore to Shirdi pilgrimage tour. Overnight trip with Urbania 17 seater, Tempo Traveller. Group rates available.'),
('Indore to Khajuraho Tour', 'indore-to-khajuraho', 'Explore the UNESCO World Heritage Khajuraho temples on a 2-day tour from Indore.', '<p>Khajuraho Group of Monuments is a UNESCO World Heritage Site famous for its stunning Nagara-style temples adorned with intricate carvings. Located 370 km from Indore, it''s a cultural and historical treasure of India.</p><p>Key temples: Kandariya Mahadeva Temple, Lakshmana Temple, Chaunsath Yogini, Chaturbhuj Temple.</p>', 'Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking', 'Hotel accommodation\nEntry fees\nFood\nPersonal expenses', 2, 1, 'Indore → Khajuraho → Indore', 'Indore', 'Khajuraho', 0, 6, 'Indore to Khajuraho Tour | UNESCO Heritage Site | OM Trip and Travels', 'Book Indore to Khajuraho 2 days trip. Group tour with Tempo Traveller, Urbania. Best rates for heritage sightseeing.'),
('Dwarka & Somnath Yatra', 'dwarka-somnath-yatra', 'Sacred Dwarka and Somnath pilgrimage from Indore — visit two of India''s most revered temples.', '<p>Embark on a divine journey to Dwarka (Lord Krishna''s kingdom) and Somnath (the first Jyotirlinga). Both temples are located in Gujarat, approximately 700-750 km from Indore, making this a 3-4 night pilgrimage tour.</p><p>Key stops: Dwarkadhish Temple, Gomti Ghat, Bet Dwarka, Somnath Jyotirlinga, Triveni Sangam.</p>', 'Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking', 'Hotel accommodation\nEntry fees\nFood\nPersonal expenses', 4, 3, 'Indore → Dwarka → Somnath → Indore', 'Indore', 'Dwarka & Somnath', 1, 7, 'Dwarka Somnath Tour from Indore | Pilgrimage Package | OM Trip and Travels', 'Book Dwarka Somnath pilgrimage from Indore. Best group tour with Urbania 17 seater, Tempo Traveller. Call for group rates.');

-- Link packages to vehicles
INSERT INTO `package_vehicles` (`package_id`, `vehicle_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 7), (1, 8),
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 7),
(3, 1), (3, 2), (3, 3), (3, 4), (3, 5), (3, 7), (3, 8),
(4, 1), (4, 2), (4, 3), (4, 4), (4, 5),
(5, 1), (5, 2), (5, 3), (5, 4), (5, 5),
(6, 1), (6, 2), (6, 3), (6, 4), (6, 5),
(7, 1), (7, 2), (7, 3), (7, 4), (7, 5);

-- FAQs
INSERT INTO `faqs` (`question`, `answer`, `category`, `sort_order`) VALUES
('What is the minimum booking requirement for Urbania or Tempo Traveller?', 'There is no minimum duration requirement. We offer hourly, daily, and multi-day packages. Call us or fill the booking form for a customized quote.', 'Booking', 1),
('Do you provide driver and fuel charges in your rental?', 'Yes, all our vehicle rentals include an experienced driver. Fuel charges are included in the quote for outstation trips. For local rentals, toll and parking may be extra — your driver will advise.', 'Pricing', 2),
('Can we book a vehicle for outstation trips from Indore?', 'Absolutely! We specialize in outstation travel from Indore to destinations across Madhya Pradesh (Ujjain, Omkareshwar, Pachmarhi, Khajuraho), Maharashtra (Shirdi, Nashik), Gujarat (Dwarka, Somnath) and all major Indian cities.', 'Booking', 3),
('Is 24/7 service available?', 'Yes, we operate 24 hours a day, 7 days a week, 365 days a year — including all holidays. We understand pilgrimage and travel needs can arise at any time.', 'General', 4),
('Do you have AC vehicles for all rentals?', 'All our vehicles are fully air-conditioned. The Urbania and Tempo Traveller fleet have powerful AC systems designed for the hot climate of Madhya Pradesh.', 'General', 5),
('What is the capacity of your largest vehicle?', 'Our largest vehicles are the Force Urbania 17 Seater and Tempo Traveller 17 Seater, accommodating up to 17 passengers comfortably with luggage.', 'Fleet', 6),
('Do you serve airport transfers?', 'Yes, we provide airport transfer services for Indore Devi Ahilyabai Holkar Airport. We serve all terminals and can accommodate solo travelers to large groups.', 'Services', 7),
('Can I book for a last-minute or same-day trip?', 'We try our best to accommodate last-minute bookings subject to vehicle availability. Call us immediately for urgent requests — we have a large fleet to ensure fast dispatch.', 'Booking', 8),
('Do you provide vehicles for weddings and events?', 'Yes! We provide decorated or standard Urbania and Tempo Travellers for wedding transport, baraat, and event transportation in and around Indore.', 'Services', 9),
('How do I confirm my booking?', 'Simply call us or fill our Booking Request form. Our team will confirm vehicle availability, provide a quote, and block your booking. We do not require online payment — your word is your booking.', 'Booking', 10);

-- Blog Categories
INSERT INTO `blog_categories` (`name`, `slug`, `description`, `sort_order`) VALUES
('Travel Tips', 'travel-tips', 'Useful tips for travelers in Madhya Pradesh and beyond.', 1),
('Pilgrimage Guides', 'pilgrimage-guides', 'Comprehensive guides to pilgrimage destinations from Indore.', 2),
('Destination Guides', 'destination-guides', 'Explore popular tourist destinations from Indore.', 3),
('Vehicle Guides', 'vehicle-guides', 'Everything you need to know about our vehicle fleet.', 4);

-- Blog Tags
INSERT INTO `blog_tags` (`name`, `slug`) VALUES
('Indore', 'indore'),
('Madhya Pradesh', 'madhya-pradesh'),
('Pilgrimage', 'pilgrimage'),
('Group Travel', 'group-travel'),
('Urbania', 'urbania'),
('Tempo Traveller', 'tempo-traveller'),
('Ujjain', 'ujjain'),
('Omkareshwar', 'omkareshwar'),
('Pachmarhi', 'pachmarhi');

-- Sample Blog Posts
INSERT INTO `blog_posts` (`category_id`, `author_id`, `title`, `slug`, `excerpt`, `content`, `status`, `published_at`, `meta_title`, `meta_description`) VALUES
(2, NULL, 'Complete Guide to Mahakaleshwar Darshan from Indore', 'mahakaleshwar-darshan-guide-from-indore', 'Planning your Mahakaleshwar Jyotirlinga darshan from Indore? This complete guide covers everything — temple timings, puja booking, best vehicles, and tips for a smooth pilgrimage.', '<h2>Introduction</h2><p>Ujjain''s Mahakaleshwar temple is one of the 12 sacred Jyotirlingas of Lord Shiva and the most visited pilgrimage site from Indore. Located just 55 km away, it is the perfect one-day spiritual getaway from the commercial capital of Madhya Pradesh.</p><h2>Temple Timings</h2><p>The Mahakaleshwar temple is open from 4:00 AM to 11:00 PM daily. The famous Bhasmarti (ash ritual) aarti takes place at 4:00 AM and is a unique, once-in-a-lifetime experience.</p><h2>Best Time to Visit</h2><p>Avoid Mondays and festival days if you want shorter queues. Weekday early morning visits (before 8 AM) are most peaceful.</p><h2>How to Reach from Indore</h2><p>Book an AC Tempo Traveller or Urbania from OM Trip and Travels for a comfortable group trip. The 55 km journey takes approximately 1–1.5 hours.</p><h2>Tips for a Smooth Darshan</h2><ul><li>Book VIP darshan tickets online in advance</li><li>Carry a small bag — large bags are not allowed inside</li><li>Wear comfortable footwear — you will walk a lot</li><li>Arrive before 7 AM for a crowd-free experience</li></ul>', 'published', '2026-01-15 09:00:00', 'Mahakaleshwar Darshan Guide from Indore | OM Trip and Travels Blog', 'Complete guide to Mahakaleshwar Jyotirlinga darshan from Indore. Temple timings, tips, best cab options. Book Urbania or Tempo Traveller for group travel.'),
(3, NULL, 'Top 7 Places to Visit Near Indore Within 200 km', 'top-places-to-visit-near-indore', 'Indore is perfectly located for weekend getaways. Here are the top 7 must-visit destinations within 200 km that you can easily explore with a rented Innova, Urbania, or Tempo Traveller.', '<h2>Introduction</h2><p>Indore, Madhya Pradesh''s largest city, is a perfect base for weekend getaways. Within 200 km, you have an incredible mix of pilgrimage sites, historical monuments, hill stations, and natural beauty.</p><h2>1. Ujjain (55 km)</h2><p>Ancient city with the Mahakaleshwar Jyotirlinga, Ram Ghat, Kal Bhairav, and the grand Kumbh Mela every 12 years.</p><h2>2. Omkareshwar (80 km)</h2><p>Sacred island temple shaped like the Om symbol on the Narmada river. A must-visit Jyotirlinga.</p><h2>3. Maheshwar (90 km)</h2><p>The capital of queen Ahilya Bai Holkar, with beautiful Narmada ghats, fort, and the famous Maheshwari handloom sarees.</p><h2>4. Mandu (100 km)</h2><p>A ruined medieval city with stunning Mughal and Afghan architecture — Jahaz Mahal, Hindola Mahal, and Roopmati Pavilion.</p><h2>5. Pachmarhi (220 km)</h2><p>The only hill station in MP with waterfalls, forests, and adventure activities.</p><h2>6. Bhimbetka (150 km)</h2><p>UNESCO World Heritage Site with 30,000-year-old rock paintings.</p><h2>7. Orchha (340 km)</h2><p>A riverside town with stunning Bundela era temples and palaces.</p><p>Book any of these trips with OM Trip and Travels for the best group travel experience from Indore!</p>', 'published', '2026-01-20 10:00:00', 'Top 7 Places to Visit Near Indore Within 200 km | OM Trip and Travels', 'Discover the best weekend getaways near Indore within 200 km. Ujjain, Omkareshwar, Maheshwar, Pachmarhi & more. Book a cab or Tempo Traveller today.');

-- Blog Post Tags
INSERT INTO `blog_post_tags` (`post_id`, `tag_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 7),
(2, 1), (2, 2), (2, 3), (2, 4);

-- Sample Testimonials
INSERT INTO `testimonials` (`name`, `designation`, `location`, `rating`, `review`, `vehicle_booked`, `is_featured`, `sort_order`) VALUES
('Rajesh Sharma', 'Business Owner', 'Indore', 5, 'We booked Urbania 17 seater for our entire family''s Shirdi trip. The vehicle was spotless, driver was professional, and the journey was extremely comfortable. Highly recommend OM Trip and Travels for group pilgrimages!', 'Force Urbania 17 Seater', 1, 1),
('Priya Patel', 'Software Engineer', 'Indore', 5, 'Used their Innova Crysta for Indore-Ujjain darshan. The car was perfectly maintained and the driver knew all the temple routes. Will definitely book again for Omkareshwar next time.', 'Toyota Innova Crysta', 1, 2),
('Suresh Verma', 'Teacher', 'Dewas', 5, 'Booked Tempo Traveller 14 seater for school trip to Pachmarhi. Extremely professional service, timely pickup, and the vehicle was in excellent condition. Students and parents both loved the trip!', 'Tempo Traveller 14 Seater', 1, 3),
('Anita Joshi', 'Homemaker', 'Indore', 5, 'OM Trip and Travels arranged Dwarka-Somnath yatra for 25 of us. They provided two Tempo Travellers, coordinated perfectly, and the entire 4-day trip was seamless. Truly blessed experience!', 'Tempo Traveller 17 Seater', 1, 4),
('Vikram Singh', 'Corporate Professional', 'Bhopal', 5, 'Hired Fortuner for a business trip from Indore. Luxury vehicle, punctual driver, smooth ride. Best cab service in Indore for sure. The booking process via phone was very quick.', 'Toyota Fortuner', 0, 5),
('Meena Agarwal', 'Retired', 'Indore', 5, 'Wonderful experience for Maheshwar and Mandu day trip. The guide information from the driver was an added bonus. Vehicle was very comfortable for senior citizens too.', 'Toyota Innova Hycross', 1, 6);

-- Sample Banners
INSERT INTO `banners` (`title`, `subtitle`, `image_path`, `cta_text`, `cta_url`, `cta_type`, `sort_order`) VALUES
('Urbania & Tempo Traveller Specialists in Indore', 'Comfortable Group Travel for Pilgrimages, Tours & Corporate Outings. 24/7 Service. Experienced Drivers.', '/assets/images/banners/banner-1.jpg', 'Call Now for Best Price', '#', 'call', 1),
('Indore Airport Taxi & Local Cab Service', 'On-time pickup & drop for all flights. AC vehicles. Professional drivers. Available 24/7.', '/assets/images/banners/banner-2.jpg', 'Book on WhatsApp', '#', 'whatsapp', 2),
('Group Tours from Indore', 'Ujjain | Omkareshwar | Maheshwar | Pachmarhi | Shirdi | Dwarka | Khajuraho', '/assets/images/banners/banner-3.jpg', 'View Tour Packages', '/packages', 'link', 3);

-- Pages SEO
INSERT INTO `pages_seo` (`page_key`, `title`, `meta_description`) VALUES
('home', 'OM Trip and Travels | Urbania & Tempo Traveller Rental in Indore', 'Book Urbania, Tempo Traveller, Innova, Fortuner on rent in Indore. Best group travel packages to Ujjain, Omkareshwar, Maheshwar, Pachmarhi, Shirdi & more.'),
('fleet', 'Vehicle Fleet | OM Trip and Travels | Indore', 'Explore our fleet of Urbania, Tempo Traveller, Innova Crysta, Innova Hycross, Fortuner on rent in Indore. Best rates for group and outstation travel.'),
('packages', 'Tour Packages from Indore | OM Trip and Travels', 'Book popular tour packages from Indore — Ujjain, Omkareshwar, Maheshwar-Mandu, Pachmarhi, Shirdi, Dwarka-Somnath. Best group travel rates.'),
('about', 'About Us | OM Trip and Travels | Indore', 'Learn about OM Trip and Travels — Indore''s trusted travel company specializing in group tours, Urbania & Tempo Traveller rentals since years.'),
('contact', 'Contact Us | OM Trip and Travels | Indore', 'Contact OM Trip and Travels for cab, taxi, Urbania, Tempo Traveller booking in Indore. Phone, WhatsApp, email and office address.'),
('blog', 'Travel Blog | OM Trip and Travels | Indore', 'Read travel tips, pilgrimage guides, and destination guides from Indore. Expert travel advice from OM Trip and Travels.'),
('gallery', 'Gallery | OM Trip and Travels', 'View our fleet and happy customers gallery. OM Trip and Travels vehicles and tour photos.'),
('testimonials', 'Customer Reviews | OM Trip and Travels', 'Read what our satisfied customers say about OM Trip and Travels. Real reviews from real travelers in Indore.'),
('faq', 'Frequently Asked Questions | OM Trip and Travels', 'Get answers to common questions about cab booking, Tempo Traveller rental, tour packages, and services at OM Trip and Travels, Indore.'),
('booking', 'Booking Request | OM Trip and Travels', 'Send a booking request to OM Trip and Travels. Fill the form and our team will call you back with availability and best price.'),
('airport-transfers', 'Airport Taxi Indore | OM Trip and Travels', 'Book airport taxi in Indore. On-time pickup and drop from Devi Ahilyabai Holkar Airport. AC vehicles, 24/7 service.'),
('privacy', 'Privacy Policy | OM Trip and Travels', 'Privacy policy of OM Trip and Travels website.'),
('terms', 'Terms and Conditions | OM Trip and Travels', 'Terms and conditions for using OM Trip and Travels services and website.');
