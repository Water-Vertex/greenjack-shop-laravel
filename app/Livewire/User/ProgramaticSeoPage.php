<?php

namespace App\Livewire\User;

use App\Models\ProgramaticSeo;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.user-layout')] 
class ProgramaticSeoPage extends Component 
{
    public string $slug;
    public ?ProgramaticSeo $record = null;
    public array $faqsArray = [];

    public function mount(string $slug): void
    {
        $this->record = ProgramaticSeo::where('slug', $slug)->firstOrFail();
        $this->parseFaqs();
    }

private function parseFaqs(): void
{
    $raw = trim((string)($this->record->faqs ?? ''));

    if (empty($raw)) {
        $this->faqsArray = [];
        return;
    }

    // Wrap in brackets if needed
    $jsonString = $raw;
    if (!str_starts_with($jsonString, '[')) {
        $jsonString = '[' . $jsonString . ']';
    }

    $decoded = json_decode($jsonString, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $faqs = [];
        foreach ($decoded as $item) {
            // Case-insensitive check for keys
            $item = array_change_key_case($item, CASE_LOWER);
            
            $q = $item['question'] ?? $item['q'] ?? '';
            $a = $item['answer'] ?? $item['a'] ?? '';
            
            if (!empty($q)) {
                $faqs[] = [
                    'question' => $q,
                    'answer' => $a ?: 'No answer available.'
                ];
            }
        }
        $this->faqsArray = $faqs;
    } else {
        // Fallback for non-JSON text
        $this->faqsArray = [[
            'question' => 'General Information',
            'answer' => $raw
        ]];
    }
}
    public function render()
    {
        return view('livewire.user.programatic-seo-page', [
            'record'    => $this->record,
            'faqsArray' => $this->faqsArray,
        ])->title($this->record->meta_title ?? $this->record->title ?? $this->record->focus_keyword);
    }
}