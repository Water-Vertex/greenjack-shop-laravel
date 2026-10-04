<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Variation;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;

class CleanOrphanVariationAttributes extends Command
{
    protected $signature = 'variations:clean-orphans {--dry-run : Sirf dikhao, save mat karo}';
    protected $description = 'Remove orphan attribute_value_ids from variations.attributes JSON column';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE — Kuch bhi save nahi hoga');
        }

        $totalVariations = Variation::count();
        $this->info("Total variations: {$totalVariations}");

        $fixedCount = 0;
        $orphanCount = 0;
        $bar = $this->output->createProgressBar($totalVariations);
        $bar->start();

        Variation::chunk(100, function ($variations) use (&$fixedCount, &$orphanCount, $dryRun, $bar) {
            foreach ($variations as $v) {
                $attrs = is_array($v->attributes)
                    ? $v->attributes
                    : json_decode($v->attributes, true) ?? [];

                if (empty($attrs)) {
                    $bar->advance();
                    continue;
                }

                $valid = [];
                $removed = [];

                foreach ($attrs as $id) {
                    $id = (int) $id;
                    if ($id > 0 && AttributeValue::where('id', $id)->exists()) {
                        $valid[] = $id;
                    } else {
                        $removed[] = $id;
                        $orphanCount++;
                    }
                }

                $valid = array_values(array_unique($valid));

                // Agar kuch remove hua ya duplicate tha
                if (count($valid) !== count($attrs)) {
                    $fixedCount++;

                    $this->newLine();
                    $this->line("Variation ID: <fg=yellow>{$v->id}</> (Product: {$v->product_id})");
                    $this->line("  Old: <fg=red>" . json_encode($attrs) . "</>");
                    $this->line("  New: <fg=green>" . json_encode($valid) . "</>");
                    if (!empty($removed)) {
                        $this->line("  Removed orphans: <fg=red>" . implode(', ', $removed) . "</>");
                    }

                    if (!$dryRun) {
                        // Direct DB update — model events avoid karne ke liye
                        DB::table('variations')
                            ->where('id', $v->id)
                            ->update([
                                'attributes' => json_encode($valid),
                                'updated_at' => now(),
                            ]);
                    }
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Done!");
        $this->line("Total variations scanned: {$totalVariations}");
        $this->line("Variations fixed: <fg=green>{$fixedCount}</>");
        $this->line("Orphan IDs removed: <fg=red>{$orphanCount}</>");

        if ($dryRun) {
            $this->newLine();
            $this->warn('⚠️  Ye DRY RUN tha — kuch bhi save nahi hua.');
            $this->info('Actual run ke liye: php artisan variations:clean-orphans');
        }

        return 0;
    }
}