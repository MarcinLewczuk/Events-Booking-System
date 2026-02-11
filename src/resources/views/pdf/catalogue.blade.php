@php
$brandColors = [
    'primary' => '#6B21A8',    // purple
    'secondary' => '#B91C1C',  // red
    'light' => '#F3E8FF',
    'dark' => '#3B0764',
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $catalogue->name }}</title>
<style>
@page { size: A4; margin: 0; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; color: #1f2937; line-height: 1.5; }
.page { width: 210mm; min-height: 297mm; page-break-after: always; position: relative; }
.page:last-child { page-break-after: auto; }

/* COVER PAGE */
.cover-page {
    background: {{ $brandColors['primary'] }};
    color: white;
    text-align: center;
    padding: 150px 40px 80px;
}
.cover-page h1 { font-size: 52pt; font-weight: 700; margin-bottom: 20px; letter-spacing: -1px; }
.cover-page .subtitle { font-size: 22pt; font-weight: 300; opacity: 0.9; margin-bottom: 40px; }
.cover-grid { display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; max-width: 600px; margin: 0 auto; }
.cover-grid img { width: calc(50% - 7.5px); height: 180px; object-fit: cover; border-radius: 12px; border: 4px solid white; }

/* INDEX PAGE */
.index-page { padding: 40px; }
.index-page h2 { font-size: 36pt; color: {{ $brandColors['primary'] }}; margin-bottom: 30px; border-bottom: 4px solid {{ $brandColors['primary'] }}; padding-bottom: 15px; }
.index-grid { display: flex; flex-direction: column; gap: 10px; margin-top: 25px; }
.index-item { display: flex; justify-content: space-between; font-size: 10pt; padding: 5px 0; }
.index-item .lot { font-weight: 700; color: {{ $brandColors['primary'] }}; }
.index-item .title { flex: 1; padding: 0 10px; }
.index-item .page { color: #6b7280; }

/* TOP ITEMS - 2 PAGE SPREAD */
.top-item-spread { padding: 30px; }
.top-item-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; border-bottom: 3px solid {{ $brandColors['primary'] }}; padding-bottom: 10px; }
.lot-number { font-size: 20pt; font-weight: 700; color: {{ $brandColors['secondary'] }}; margin-bottom: 8px; }
.top-item-header h3 { font-size: 26pt; color: {{ $brandColors['primary'] }}; font-weight: 700; line-height: 1.2; }
.estimate { font-size: 16pt; font-weight: 700; color: {{ $brandColors['primary'] }}; text-align: right; }

.top-item-images, .additional-images { display: flex; gap: 10px; margin-top: 20px; }
.top-item-images img, .additional-images img { width: 32%; height: auto; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb; }

/* ITEM DETAILS */
.item-details { margin-top: 20px; }
.detail-row { display: flex; margin-bottom: 10px; background: {{ $brandColors['light'] }}; padding: 8px 12px; border-radius: 6px; }
.detail-label { font-weight: 700; width: 130px; color: {{ $brandColors['dark'] }}; font-size: 9pt; }
.detail-value { flex: 1; font-size: 9pt; color: #4b5563; }

/* TAGS */
.item-tags { margin-top: 15px; display: flex; flex-wrap: wrap; gap: 6px; }
.tag { background: {{ $brandColors['primary'] }}; color: white; padding: 5px 12px; border-radius: 16px; font-size: 8pt; font-weight: 600; }

/* MID ITEMS - 1 PAGE */
.mid-item-page { padding: 30px; }
.mid-item-content { display: flex; gap: 20px; margin-top: 20px; }
.mid-item-image { width: 45%; height: auto; max-height: 400px; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb; }

/* BOTTOM ITEMS - HALF PAGE */
.bottom-items-page { padding: 25px; }
.bottom-item { display: flex; gap: 15px; margin-bottom: 15px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; }
.bottom-item img { width: 120px; height: auto; max-height: 100px; object-fit: cover; border-radius: 6px; border: 2px solid #e5e7eb; }
.bottom-item h4 { font-size: 14pt; color: {{ $brandColors['primary'] }}; font-weight: 700; margin-bottom: 5px; }
.bottom-item-desc { font-size: 9pt; color: #6b7280; line-height: 1.4; }

/* COMING SOON */
.coming-soon-page { padding: 80px 60px; text-align: center; }
.coming-soon-page h2 { font-size: 40pt; color: {{ $brandColors['primary'] }}; margin-bottom: 20px; font-weight: 700; }
.coming-soon-page p { font-size: 14pt; color: #6b7280; line-height: 1.6; }
.coming-soon-page .italic-note { font-style: italic; color: #9ca3af; margin-top: 20px; }
</style>
</head>
<body>

{{-- COVER PAGE --}}
<div class="page cover-page">
    <h1>{{ $catalogue->name }}</h1>
    <div class="subtitle">{{ $catalogue->category->name ?? 'Auction Catalogue' }}</div>
    @if($featuredItems->isNotEmpty())
    <div class="cover-grid">
        @foreach($featuredItems as $item)
            @if($item->primaryImage)
                <img src="{{ public_path('storage/' . $item->primaryImage->path) }}" alt="{{ $item->title }}">
            @endif
        @endforeach
    </div>
    @endif
</div>

{{-- INDEX PAGE --}}
<div class="page index-page">
    <h2>Index of Lots</h2>
    <div class="index-grid">
        @foreach($allItems as $index => $item)
            <div class="index-item">
                <span class="lot">Lot {{ $item->pivot->lot_number }}</span>
                <span class="title">{{ Str::limit($item->title, 45) }}</span>
                <span class="page">{{ $index + 3 }}</span>
            </div>
        @endforeach
    </div>
</div>

{{-- TOP 20 ITEMS --}}
@foreach($topItems as $item)
<div class="page top-item-spread">
    <div class="top-item-header">
        <div style="flex:1;">
            <div class="lot-number">Lot {{ $item->pivot->lot_number }}</div>
            <h3>{{ $item->pivot->title_override ?? $item->title }}</h3>
        </div>
        @if($item->band)
        <div class="estimate">£{{ number_format($item->band->min_price) }}@if($item->band->max_price) - £{{ number_format($item->band->max_price) }}@endif</div>
        @endif
    </div>
    <div class="item-details">
        @if($item->pivot->description_override ?? $item->description)
        <div class="detail-row">
            <div class="detail-label">Description:</div>
            <div class="detail-value">{{ $item->pivot->description_override ?? $item->description }}</div>
        </div>
        @endif
    </div>
</div>

{{-- SECOND PAGE FOR ADDITIONAL IMAGES --}}
@if($item->images->count() > 1)
<div class="page">
    <h3 style="color: {{ $brandColors['primary'] }}; margin-bottom: 15px;">Images - Lot {{ $item->pivot->lot_number }}</h3>
    <div class="additional-images">
        @foreach($item->images as $image)
            <img src="{{ public_path('storage/' . $image->path) }}" alt="{{ $item->title }}">
        @endforeach
    </div>
</div>
@endif
@endforeach

{{-- NEXT 30 ITEMS --}}
@foreach($midItems as $item)
<div class="page mid-item-page">
    <div class="top-item-header">
        <div style="flex:1;">
            <div class="lot-number">Lot {{ $item->pivot->lot_number }}</div>
            <h3>{{ $item->pivot->title_override ?? $item->title }}</h3>
        </div>
        @if($item->band)
        <div class="estimate">£{{ number_format($item->band->min_price) }}@if($item->band->max_price) - £{{ number_format($item->band->max_price) }}@endif</div>
        @endif
    </div>
    <div class="mid-item-content">
        @if($item->primaryImage)
        <img src="{{ public_path('storage/' . $item->primaryImage->path) }}" class="mid-item-image">
        @endif
        <div class="item-details">
            @if($item->pivot->description_override ?? $item->description)
            <div class="detail-row">
                <div class="detail-label">Description:</div>
                <div class="detail-value">{{ Str::limit($item->pivot->description_override ?? $item->description, 300) }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach

{{-- REMAINING ITEMS --}}
@foreach($bottomItems->chunk(2) as $chunk)
<div class="page bottom-items-page">
    @foreach($chunk as $item)
        <div class="bottom-item">
            @if($item->primaryImage)
            <img src="{{ public_path('storage/' . $item->primaryImage->path) }}" alt="{{ $item->title }}">
            @endif
            <div>
                <h4>{{ $item->pivot->title_override ?? $item->title }}</h4>
                <p class="bottom-item-desc">{{ Str::limit($item->pivot->description_override ?? $item->description, 150) }}</p>
            </div>
        </div>
    @endforeach
</div>
@endforeach

{{-- COMING SOON PAGE --}}
@if(!$isComplete)
<div class="page coming-soon-page">
    <h2>More Lots Coming Soon</h2>
    <p>This catalogue is still being finalized. Additional lots will be added before the auction date.</p>
    <p class="italic-note">Please check back for updates or visit our website.</p>
</div>
@endif

</body>
</html>
