<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelPdf\Facades\Pdf;

class CataloguePdfController extends Controller
{
    /**
     * Generate and view PDF for a catalogue
     */
    public function generate(Catalogue $catalogue)
    {
        $this->authorize('view', $catalogue);

        Log::info("PDF Generate requested for catalogue: {$catalogue->id}");

        // Check if PDF already exists
        $pdfPath = "catalogues/{$catalogue->id}.pdf";
        
        if (Storage::disk('public')->exists($pdfPath)) {
            Log::info("Returning existing PDF: {$pdfPath}");
            return response()->file(storage_path("app/public/{$pdfPath}"));
        }

        // Generate new PDF
        Log::info("Generating new PDF for catalogue: {$catalogue->id}");
        return $this->createPdf($catalogue);
    }

    /**
     * Force regenerate PDF
     */
    public function regenerate(Catalogue $catalogue)
    {
        $this->authorize('update', $catalogue);

        Log::info("PDF Regenerate requested for catalogue: {$catalogue->id}");

        // Delete existing PDF
        $pdfPath = "catalogues/{$catalogue->id}.pdf";
        Storage::disk('public')->delete($pdfPath);
        Log::info("Deleted existing PDF: {$pdfPath}");

        // Generate new PDF
        return $this->createPdf($catalogue, true);
    }

    /**
     * Create PDF file
     */
    private function createPdf(Catalogue $catalogue, $save = true)
    {
        try {
            Log::info("Starting createPdf for catalogue: {$catalogue->id}");
            
            // Increase time limit for PDF generation
            set_time_limit(300);
            ini_set('memory_limit', '512M');

            Log::info("Loading catalogue relationships...");
            // Load catalogue with all relationships
            $catalogue->load([
                'items.images',
                'items.band',
                'items.location',
                'items.tags',
                'category'
            ]);

            Log::info("Catalogue has {$catalogue->items->count()} items");

            // Get brand colors
            $brandColors = $this->getBrandColors();

            // Organize items by tier
            $items = $catalogue->items->sortBy('pivot.display_order');
            $topItems = $items->take(20);
            $midItems = $items->slice(20, 30);
            $bottomItems = $items->slice(50);

            // Check if catalogue is complete
            $isComplete = $catalogue->status === 'published';

            // Get featured items for cover
            $featuredItems = $items->filter(fn($item) => $item->images->isNotEmpty())->take(4);

            Log::info("Featured items: {$featuredItems->count()}");

            $data = [
                'catalogue' => $catalogue,
                'brandColors' => $brandColors,
                'topItems' => $topItems,
                'midItems' => $midItems,
                'bottomItems' => $bottomItems,
                'allItems' => $items,
                'isComplete' => $isComplete,
                'featuredItems' => $featuredItems,
            ];

            Log::info("Creating PDF view...");

            // Try to find Chrome/Chromium
            $chromePath = $this->findChromePath();
            Log::info("Using Chrome path: {$chromePath}");

            // Generate PDF using Spatie
            $pdf = Pdf::view('pdf.catalogue', $data)
                ->format('a4')
                ->margins(0, 0, 0, 0)
                ->withBrowsershot(function ($browsershot) use ($chromePath) {
                    $browsershot
                        ->setChromePath($chromePath)
                        ->noSandbox()
                        ->setOption('args', [
                            '--disable-web-security',
                            '--disable-dev-shm-usage',
                            '--no-first-run',
                            '--disable-gpu'
                        ])
                        ->dismissDialogs()
                        ->timeout(240);
                });

            Log::info("PDF object created, saving...");

            if ($save) {
                // Ensure directory exists
                $directory = storage_path('app/public/catalogues');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                    Log::info("Created directory: {$directory}");
                }

                // Save to storage
                $pdfPath = "catalogues/{$catalogue->id}.pdf";
                $fullPath = storage_path("app/public/{$pdfPath}");
                
                $pdf->save($fullPath);
                
                Log::info("PDF saved to: {$fullPath}");
                
                if (file_exists($fullPath)) {
                    Log::info("PDF file confirmed exists, size: " . filesize($fullPath) . " bytes");
                    return response()->file($fullPath);
                } else {
                    Log::error("PDF file does not exist after save!");
                    throw new \Exception("PDF was not saved successfully");
                }
            }

            Log::info("Streaming PDF download...");
            return $pdf->download("catalogue-{$catalogue->id}.pdf");

        } catch (\Exception $e) {
            Log::error('PDF Generation Failed', [
                'catalogue_id' => $catalogue->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Find Chrome/Chromium executable path
     */
    private function findChromePath()
    {
        $paths = [
            '/usr/bin/google-chrome-stable',
            '/usr/bin/google-chrome',
            '/usr/bin/chromium-browser',
            '/usr/bin/chromium',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Default fallback
        return '/usr/bin/google-chrome';
    }

    /**
     * Get brand colors
     */
    private function getBrandColors()
    {
        return [
            'primary' => '#1e40af',
            'secondary' => '#f59e0b',
            'accent' => '#10b981',
            'dark' => '#1f2937',
            'light' => '#f3f4f6',
        ];
    }

    /**
     * Download existing PDF
     */
    public function download(Catalogue $catalogue)
    {
        $this->authorize('view', $catalogue);

        Log::info("PDF Download requested for catalogue: {$catalogue->id}");

        $pdfPath = "catalogues/{$catalogue->id}.pdf";
        
        if (!Storage::disk('public')->exists($pdfPath)) {
            Log::info("PDF doesn't exist, generating...");
            return $this->createPdf($catalogue);
        }

        Log::info("Downloading existing PDF: {$pdfPath}");
        return Storage::disk('public')->download($pdfPath, "catalogue-{$catalogue->name}.pdf");
    }
}