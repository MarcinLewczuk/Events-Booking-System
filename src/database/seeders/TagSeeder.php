<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Antique', 'description' => 'Items over 100 years old'],
            ['name' => 'Museum Quality', 'description' => 'Suitable for museum collections'],
            ['name' => 'Investment Grade', 'description' => 'High-value investment pieces'],
            ['name' => 'Masterpiece', 'description' => 'Exceptional artistic importance'],
            ['name' => 'Old Master', 'description' => 'Created by an Old Master artist'],
            ['name' => 'Fine Art', 'description' => 'High-quality fine art works'],
            ['name' => 'Modern Art', 'description' => '20th century modern artworks'],
            ['name' => 'Contemporary Art', 'description' => 'Post-war contemporary works'],
            ['name' => 'Impressionist', 'description' => 'Impressionist movement artworks'],
            ['name' => 'Post-Impressionist', 'description' => 'Post-Impressionist period pieces'],
            ['name' => 'Art Deco', 'description' => 'Art Deco design and style'],
            ['name' => 'Art Nouveau', 'description' => 'Art Nouveau decorative style'],
            ['name' => 'Victorian', 'description' => 'Victorian era works'],
            ['name' => 'Edwardian', 'description' => 'Edwardian period items'],
            ['name' => 'Mid-Century Modern', 'description' => 'Mid-20th century modern design'],
            ['name' => 'Rare', 'description' => 'Extremely uncommon or scarce'],
            ['name' => 'Ultra Rare', 'description' => 'Exceptionally rare items'],
            ['name' => 'One of a Kind', 'description' => 'Unique, singular piece'],
            ['name' => 'Limited Edition', 'description' => 'Produced in limited quantities'],
            ['name' => 'Numbered Edition', 'description' => 'Individually numbered edition'],
            ['name' => 'Signed', 'description' => 'Signed by artist or maker'],
            ['name' => 'Monogrammed', 'description' => 'Bearing personal monogram'],
            ['name' => 'Attributed', 'description' => 'Attributed to a known artist'],
            ['name' => 'After', 'description' => 'Created after the original artist'],
            ['name' => 'Circle Of', 'description' => 'From the circle of a known artist'],
            ['name' => 'Workshop Of', 'description' => 'Produced in the workshop of an artist'],
            ['name' => 'Estate Fresh', 'description' => 'Fresh to market from estate'],
            ['name' => 'Private Collection', 'description' => 'From a private collection'],
            ['name' => 'Royal Provenance', 'description' => 'Previously owned by royalty'],
            ['name' => 'Noble Provenance', 'description' => 'Previously owned by nobility'],
            ['name' => 'Historic Provenance', 'description' => 'Documented historical ownership'],
            ['name' => 'Provenance', 'description' => 'Documented ownership history'],
            ['name' => 'Exhibited', 'description' => 'Previously exhibited publicly'],
            ['name' => 'Published', 'description' => 'Referenced in scholarly publications'],
            ['name' => 'Catalogued', 'description' => 'Listed in major catalogues'],
            ['name' => 'Literature Referenced', 'description' => 'Mentioned in academic literature'],
            ['name' => 'Award Winning', 'description' => 'Recipient of notable awards'],
            ['name' => 'Iconic', 'description' => 'Recognized iconic work'],
            ['name' => 'Period Piece', 'description' => 'Authentic to its historical period'],
            ['name' => 'Original', 'description' => 'Original, not a reproduction'],
            ['name' => 'Authentic', 'description' => 'Verified authenticity'],
            ['name' => 'Certified', 'description' => 'Accompanied by certification'],
            ['name' => 'Archival', 'description' => 'Archival-quality materials'],
            ['name' => 'Handcrafted', 'description' => 'Individually handcrafted'],
            ['name' => 'Master Crafted', 'description' => 'Exceptional craftsmanship'],
            ['name' => 'Luxury', 'description' => 'High-end luxury item'],
            ['name' => 'Designer', 'description' => 'Created by a notable designer'],
            ['name' => 'Haute Couture', 'description' => 'High fashion couture'],
            ['name' => 'Bespoke', 'description' => 'Custom-made piece'],
            ['name' => 'Decorative Arts', 'description' => 'Decorative art objects'],
            ['name' => 'Sculpture', 'description' => 'Three-dimensional artwork'],
            ['name' => 'Painting', 'description' => 'Painted artwork'],
            ['name' => 'Drawing', 'description' => 'Original drawing or sketch'],
            ['name' => 'Print', 'description' => 'Fine art print'],
            ['name' => 'Photography', 'description' => 'Fine art photography'],
            ['name' => 'Silver', 'description' => 'Silver or silver-gilt items'],
            ['name' => 'Gold', 'description' => 'Gold or gold-mounted pieces'],
            ['name' => 'Jewelry', 'description' => 'Fine jewelry items'],
            ['name' => 'Gemstone', 'description' => 'Featuring precious gemstones'],
            ['name' => 'Timepiece', 'description' => 'Luxury watches or clocks'],
            ['name' => 'Horology', 'description' => 'Fine horological items'],
            ['name' => 'Antique Furniture', 'description' => 'Historic furniture pieces'],
            ['name' => 'Design Furniture', 'description' => 'Designer furniture'],
            ['name' => 'Ceramics', 'description' => 'Ceramic and porcelain works'],
            ['name' => 'Porcelain', 'description' => 'Fine porcelain items'],
            ['name' => 'Glass', 'description' => 'Art glass works'],
            ['name' => 'Murano', 'description' => 'Murano glass pieces'],
            ['name' => 'Bronze', 'description' => 'Bronze sculptures or objects'],
            ['name' => 'Marble', 'description' => 'Marble works of art'],
            ['name' => 'Textiles', 'description' => 'Historic or decorative textiles'],
            ['name' => 'Tapestry', 'description' => 'Woven tapestry works'],
            ['name' => 'Manuscript', 'description' => 'Historical manuscripts'],
            ['name' => 'Rare Book', 'description' => 'Scarce or valuable books'],
            ['name' => 'First Edition', 'description' => 'First edition publications'],
            ['name' => 'Illuminated', 'description' => 'Illuminated manuscripts or works'],
            ['name' => 'Cartography', 'description' => 'Antique maps and atlases'],
            ['name' => 'Militaria', 'description' => 'Historical military artifacts'],
            ['name' => 'Nautical', 'description' => 'Maritime-related antiques'],
            ['name' => 'Scientific Instrument', 'description' => 'Historic scientific tools'],
            ['name' => 'Restored', 'description' => 'Professionally restored'],
            ['name' => 'Conserved', 'description' => 'Expertly conserved condition'],
            ['name' => 'As-Is', 'description' => 'Offered in current condition'],
            ['name' => 'Collector’s Item', 'description' => 'Highly desirable for collectors'],
            ['name' => 'Auction Highlight', 'description' => 'Featured auction lot'],

        ];

        foreach ($tags as $tagData) {
            Tag::create([
                'name' => $tagData['name'],
                'slug' => Str::slug($tagData['name']),
                'description' => $tagData['description'],
            ]);
        }
    }
}