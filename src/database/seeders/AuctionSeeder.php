<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auction;
use App\Models\Catalogue;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $catalogues = Catalogue::where('status', 'published')->get();
        $locations = Location::all();

        if (!$admin || $catalogues->isEmpty() || $locations->isEmpty()) {
            return;
        }

        $auctions = [
            [
                'catalogue_id' => $catalogues->first()->id,
                'title' => 'Spring Fine Art Auction 2026',
                'auction_date' => Carbon::now()->addDays(15),
                'start_time' => '14:00:00',
                'status' => 'scheduled',
                'approval_status' => 'approved',
                'approved_by' => $admin->id,
                'approval_status_changed_at' => now()->subDays(3),
                'rejection_reason' => null,
                'created_by' => $admin->id,
                'location_id' => $locations->first()->id,
                'auction_block' => 'Block A',
            ],
            [
                'catalogue_id' => $catalogues->skip(1)->first()->id ?? $catalogues->first()->id,
                'title' => 'Antique Furniture & Furnishings',
                'auction_date' => Carbon::now()->addDays(30),
                'start_time' => '10:00:00',
                'status' => 'scheduled',
                'approval_status' => 'awaiting_approval',
                'approved_by' => null,
                'approval_status_changed_at' => now()->subDays(2),
                'rejection_reason' => null,
                'created_by' => $admin->id,
                'location_id' => $locations->skip(1)->first()?->id ?? $locations->first()->id,
                'auction_block' => 'Block B',
            ],
            [
                'catalogue_id' => $catalogues->last()->id,
                'title' => 'Contemporary Ceramics Exhibition Sale',
                'auction_date' => Carbon::now()->addDays(45),
                'start_time' => '18:00:00',
                'status' => 'scheduled',
                'approval_status' => 'approved',
                'approved_by' => $admin->id,
                'approval_status_changed_at' => now()->subDays(5),
                'rejection_reason' => null,
                'created_by' => $admin->id,
                'location_id' => $locations->first()->id,
                'auction_block' => null,
            ],
            [
                'catalogue_id' => $catalogues->first()->id,
                'title' => 'Summer Collectibles Auction',
                'auction_date' => Carbon::now()->addDays(60),
                'start_time' => '13:00:00',
                'status' => 'scheduled',
                'approval_status' => 'draft',
                'approved_by' => null,
                'approval_status_changed_at' => null,
                'rejection_reason' => null,
                'created_by' => $admin->id,
                'location_id' => $locations->skip(2)->first()?->id ?? $locations->first()->id,
                'auction_block' => 'Block C',
            ],
        ];

        foreach ($auctions as $auctionData) {
            Auction::create($auctionData);
        }
    }
}