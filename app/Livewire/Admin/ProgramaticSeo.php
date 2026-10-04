<?php

namespace App\Livewire\Admin;

use App\Models\ProgramaticSeo as ProgramaticSeoModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

#[Layout('components.admin-layout')]
class ProgramaticSeo extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $csvFile;
    public $importMessage = '';
    public $importSuccess = false;
    public $importSummary = ['new' => 0, 'updated' => 0, 'skipped' => 0];
    public $uploading = false;
    public $fileInputKey = '';

    public $showFormModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    public $deletingId = null;

    public $title, $slug, $meta_title, $meta_description, $meta_keywords, $meta_tags,
           $page_schema, $focus_keyword, $content, $image, $image_alt, $h1_heading,
           $faqs, $section_content_left, $section_content_right, $image_left,
           $image_right, $image_left_alt, $image_right_alt;

    protected $queryString = ['search' => ['except' => ''], 'perPage' => ['except' => 10]];


        protected $rules = [
        'focus_keyword' => 'required|string|max:255',
        'title' => 'nullable|string|max:255',
        'slug' => 'nullable|string|max:255',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'meta_tags' => 'nullable|string',
        'page_schema' => 'nullable|string',
        'content' => 'nullable|string',
        'image' => 'nullable|url|max:500',
        'image_alt' => 'nullable|string|max:255',
        'h1_heading' => 'nullable|string|max:255',
        'faqs' => 'nullable|string',
        'section_content_left' => 'nullable|string',
        'section_content_right' => 'nullable|string',
        'image_left' => 'nullable|url|max:500',
        'image_right' => 'nullable|url|max:500',
        'image_left_alt' => 'nullable|string|max:255',
        'image_right_alt' => 'nullable|string|max:255',
    ];

    public function mount() { $this->fileInputKey = uniqid(); }

    public function importFile()
    {
        $this->validate(['csvFile' => 'required|file|mimes:csv,xls,xlsx,txt|max:10240']);

        $fileHash = md5_file($this->csvFile->getRealPath());
        $dbHasData = ProgramaticSeoModel::exists();

        // Check hash ONLY if database is NOT empty
        if ($dbHasData && Session::get('last_imported_file_hash') === $fileHash) {
            $this->importMessage = 'This file has already been imported. No changes were made.';
            $this->importSuccess = false;
            $this->resetCsvFile();
            return;
        }

        $this->uploading = true;
        try {
            $path = $this->csvFile->getRealPath();
            $extension = strtolower($this->csvFile->getClientOriginalExtension());
            $rows = in_array($extension, ['xls', 'xlsx'])
                ? IOFactory::load($path)->getActiveSheet()->toArray(null, true, true, false)
                : array_map('str_getcsv', file($path));

            $rows = array_values(array_filter($rows, fn($row) => !empty(array_filter($row))));
            if (count($rows) < 2) throw new \Exception('No data found in file.');

            $header = array_map(fn($h) => strtolower(str_replace([' ', "\xEF\xBB\xBF"], ['_', ''], trim((string)$h))), $rows[0]);
            unset($rows[0]);

            $counts = ['new' => 0, 'updated' => 0, 'skipped' => 0];
            foreach ($rows as $r) {
                $r = array_values($r);
                if (count($header) !== count($r)) continue;
                $data = array_combine($header, $r);
                if (empty(trim($data['focus_keyword'] ?? ''))) continue;

                $mappedData = $this->mapImportRow($data);
                if (empty($mappedData['slug'])) $mappedData['slug'] = Str::slug($mappedData['focus_keyword']);

                $existing = ProgramaticSeoModel::where('focus_keyword', $mappedData['focus_keyword'])->first();
                if ($existing) {
                    $isDifferent = false;
                    foreach ($mappedData as $key => $value) {
                        if (trim((string)($existing->$key ?? '')) !== trim((string)($value ?? ''))) { $isDifferent = true; break; }
                    }
                    if ($isDifferent) { $existing->update($mappedData); $counts['updated']++; }
                    else { $counts['skipped']++; }
                } else {
                    ProgramaticSeoModel::create($mappedData); $counts['new']++;
                }
            }

            $this->importSummary = $counts;
            $this->importSuccess = true;
            $this->importMessage = ($counts['new'] > 0 || $counts['updated'] > 0) ? "Import completed successfully!" : "No changes detected this file is already uploaded.";

            Session::put('last_imported_file_hash', $fileHash);
            $this->resetCsvFile();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->importSuccess = false;
            $this->importMessage = 'Error: ' . $e->getMessage();
            $this->resetCsvFile();
        }
        $this->uploading = false;
    }

    private function resetCsvFile() { $this->csvFile = null; $this->fileInputKey = uniqid(); }

    private function mapImportRow($data) {
        $mapped = [];
        foreach ($this->formFieldNames() as $field) { $mapped[$field] = isset($data[$field]) ? trim((string)$data[$field]) : null; }
        return $mapped;
    }

    private function formFieldNames() {
        return ['title', 'slug', 'meta_title', 'meta_description', 'meta_keywords', 'meta_tags', 'page_schema', 'focus_keyword', 'content', 'image', 'image_alt', 'h1_heading', 'faqs', 'section_content_left', 'section_content_right', 'image_left', 'image_right', 'image_left_alt', 'image_right_alt'];
    }

  public function openEditModal($id)
{
    $record = ProgramaticSeoModel::findOrFail($id);
    
    $this->editingId = $id;
    
    // Form fields ko populate karein
    $this->focus_keyword = $record->focus_keyword;
    $this->title = $record->title;
    $this->slug = $record->slug;
    $this->h1_heading = $record->h1_heading;
    $this->meta_title = $record->meta_title;
    $this->meta_keywords = $record->meta_keywords;
    $this->meta_description = $record->meta_description;
    $this->meta_tags = $record->meta_tags;
    $this->page_schema = $record->page_schema;
    $this->content = $record->content;
    $this->image = $record->image;
    $this->image_alt = $record->image_alt;
    $this->image_left = $record->image_left;
    $this->image_left_alt = $record->image_left_alt;
    $this->section_content_left = $record->section_content_left;
    $this->image_right = $record->image_right;
    $this->image_right_alt = $record->image_right_alt;
    $this->section_content_right = $record->section_content_right;
    $this->faqs = $record->faqs;

    $this->showFormModal = true;
}

   public function save()
    {
        // ✅ Ab validation rules exist karte hain
        $this->validate();

        $data = [];
        foreach ($this->formFieldNames() as $field) {
            // Empty string ko null mein convert karo (optional)
            $data[$field] = ($this->$field !== '') ? $this->$field : null;
        }
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['focus_keyword'] ?? '');
        }

        ProgramaticSeoModel::findOrFail($this->editingId)->update($data);
        $this->showFormModal = false;
        session()->flash('success', 'Record updated successfully!');

        $this->resetPage();
    }


    public function confirmDelete($id) { $this->deletingId = $id; $this->showDeleteModal = true; }

   public function deleteRecord() 
{
    if ($this->deletingId) {
        // Record dhoond kar delete karein
        ProgramaticSeoModel::findOrFail($this->deletingId)->delete();
        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'Record deleted successfully!');
        $this->resetPage(); 
    }
}

    public function sortBy($field) {
        $this->sortDirection = ($this->sortField === $field && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
    }


    public function closeModal()
{
    $this->showFormModal = false;
    $this->editingId = null;
    $this->reset([
        'focus_keyword', 'title', 'slug', 'h1_heading', 'meta_title', 
        'meta_keywords', 'meta_description', 'meta_tags', 'page_schema', 
        'content', 'image', 'image_alt', 'image_left', 'image_left_alt', 
        'section_content_left', 'image_right', 'image_right_alt', 
        'section_content_right', 'faqs'
    ]);
}

    public function render() {
        return view('livewire.admin.programatic-seo', [
            'records' => ProgramaticSeoModel::where('focus_keyword', 'like', "%{$this->search}%")->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage),
            'totalCount' => ProgramaticSeoModel::count(),
        ]);
    }
}