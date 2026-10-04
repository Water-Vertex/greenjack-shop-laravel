<?php

namespace App\Livewire\User;

use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Category;
use App\Models\Problem;
use App\Models\RepairRequest;
use App\Models\Series;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.user-layout')]
class Repair extends Component
{
    // Selected values
    public $selectedCategory = null;
    public $selectedBrand = null;
    public $selectedModel = null;
    public $selectedProblems = [];

    // Data collections
    public $categories = [];
    public $brands = [];
    public $models = [];
    public $problems = [];
    public $seriesList; // This will be a Collection
    public $expandedSeries = null; // Track expanded series

    // Search
    public $modelSearch = '';
    public $problemSearch = '';

    // Current step
    public $currentStep = 1;

    // Form data
    public $formData = [
        'category' => null,
        'brand' => null,
        'model' => null,
        'problems' => [],
        'total_price' => 0,
        'type' => null,
        'date' => null,
        'time' => null,
        // Customer details
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'mobile' => '',
        'imei' => '',
        'address' => '',
        'zip_code' => '',
        'city' => '',
        'state' => '',
        'country' => '',
        'message' => '',
        'terms' => false,
    ];

    protected $rules = [
        'selectedCategory' => 'required',
        'selectedBrand' => 'required',
        'selectedModel' => 'required',
        'selectedProblems' => 'required|array|min:1',
        'formData.type' => 'required',
        'formData.date' => 'required|date',
        'formData.time' => 'required',
        'formData.first_name' => 'required|string|max:255',
        'formData.last_name' => 'required|string|max:255',
        'formData.email' => 'required|email|max:255',
        'formData.mobile' => 'required|string|max:20',
        'formData.imei' => 'nullable|string|max:50',
        'formData.address' => 'nullable|string',
        'formData.zip_code' => 'nullable|string|max:20',
        'formData.city' => 'nullable|string|max:100',
        'formData.state' => 'nullable|string|max:100',
        'formData.country' => 'nullable|string|max:100',
        'formData.message' => 'nullable|string',
        'formData.terms' => 'accepted',
    ];

    protected $messages = [
        'selectedCategory.required' => 'Please select a category.',
        'selectedBrand.required' => 'Please select a brand.',
        'selectedModel.required' => 'Please select a model.',
        'selectedProblems.required' => 'Please select at least one problem.',
        'selectedProblems.min' => 'Please select at least one problem.',
        'formData.type.required' => 'Please select how you heard about us.',
        'formData.date.required' => 'Please select a preferred date.',
        'formData.time.required' => 'Please select a preferred time.',
        'formData.first_name.required' => 'Please enter your first name.',
        'formData.last_name.required' => 'Please enter your last name.',
        'formData.email.required' => 'Please enter your email address.',
        'formData.email.email' => 'Please enter a valid email address.',
        'formData.mobile.required' => 'Please enter your mobile number.',
        'formData.terms.accepted' => 'You must accept the terms and conditions.',
    ];

    public function mount()
    {
        $this->categories = Category::get();
        $this->brands = collect();
        $this->models = collect();
        $this->problems = collect();
        $this->seriesList = collect(); // Initialize as Collection
        $this->selectedProblems = [];
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->formData['category'] = $categoryId;

        $this->brands = Brand::whereHas('categories', function($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })->get();

        $this->resetBrandAndModel();
        $this->selectedProblems = [];
        $this->formData['problems'] = [];
        $this->formData['total_price'] = 0;
        $this->problems = collect();
        $this->seriesList = collect(); // Reset as Collection
        $this->expandedSeries = null;

        $this->currentStep = 2;
    }

    public function selectBrand($brandId)
    {
        $this->selectedBrand = $brandId;
        $this->formData['brand'] = $brandId;

        // Check if brand has series for this category
        $this->loadSeriesAndModels();

        $this->selectedModel = null;
        $this->selectedProblems = [];
        $this->formData['model'] = null;
        $this->formData['problems'] = [];
        $this->formData['total_price'] = 0;
        $this->modelSearch = '';
        $this->problems = collect();

        $this->currentStep = 3;
    }

    public function loadSeriesAndModels()
    {
        // Get series that belong to this category and brand
        $this->seriesList = Series::where('category_id', $this->selectedCategory)
            ->where('brand_id', $this->selectedBrand)
            ->with('models')
            ->get();

        // Get models that don't belong to any series (standalone models)
        $modelsInSeries = collect(); // Initialize as Collection
        foreach ($this->seriesList as $series) {
            foreach ($series->models as $model) {
                $modelsInSeries->push($model->id);
            }
        }

        $this->models = BrandModel::where('category_id', $this->selectedCategory)
            ->where('brand_id', $this->selectedBrand)
            ->whereNotIn('id', $modelsInSeries)
            ->orderBy('sorting_order','asc')
            ->get();
    }

    public function toggleSeries($seriesId)
    {
        if ($this->expandedSeries == $seriesId) {
            $this->expandedSeries = null;
        } else {
            $this->expandedSeries = $seriesId;
        }
    }

    public function selectModel($modelId)
    {
        $this->selectedModel = $modelId;
        $this->formData['model'] = $modelId;

        $this->problems = Problem::where('brand_model_id', $modelId)
            ->orderBy('name')
            ->get();

        $this->selectedProblems = [];
        $this->formData['problems'] = [];
        $this->formData['total_price'] = 0;
        $this->problemSearch = '';

        $this->currentStep = 4;
    }

    public function toggleProblem($problemId)
    {
        $problem = Problem::find($problemId);

        if (in_array($problemId, $this->selectedProblems)) {
            $this->selectedProblems = array_diff($this->selectedProblems, [$problemId]);
            unset($this->formData['problems'][$problemId]);
        } else {
            $this->selectedProblems[] = $problemId;
            $this->formData['problems'][$problemId] = [
                'id' => $problemId,
                'name' => $problem->name,
                'price' => $problem->discounted_price ?? $problem->price,
                'description' => $problem->description
            ];
        }

        $this->calculateTotalPrice();
    }

    public function removeProblem($problemId)
    {
        $this->selectedProblems = array_diff($this->selectedProblems, [$problemId]);
        unset($this->formData['problems'][$problemId]);
        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        $total = 0;
        foreach ($this->formData['problems'] as $problem) {
            $total += floatval($problem['price']);
        }
        $this->formData['total_price'] = $total;
    }

    public function resetBrandAndModel()
    {
        $this->selectedBrand = null;
        $this->selectedModel = null;
        $this->formData['brand'] = null;
        $this->formData['model'] = null;
        $this->models = collect();
        $this->seriesList = collect();
        $this->modelSearch = '';
        $this->expandedSeries = null;
    }

    public function goToStep($step)
    {
        if ($step == 2 && !$this->selectedCategory) {
            session()->flash('error', 'Please select a category first.');
            return;
        }
        if ($step == 3 && !$this->selectedBrand) {
            session()->flash('error', 'Please select a brand first.');
            return;
        }
        if ($step == 4 && !$this->selectedModel) {
            session()->flash('error', 'Please select a model first.');
            return;
        }
        if ($step == 5 && empty($this->selectedProblems)) {
            session()->flash('error', 'Please select at least one problem.');
            return;
        }

        if ($step <= $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    public function nextStep()
    {
        if ($this->currentStep == 1 && !$this->selectedCategory) {
            session()->flash('error', 'Please select a category.');
            return;
        }
        if ($this->currentStep == 2 && !$this->selectedBrand) {
            session()->flash('error', 'Please select a brand.');
            return;
        }
        if ($this->currentStep == 3 && !$this->selectedModel) {
            session()->flash('error', 'Please select a model.');
            return;
        }
        if ($this->currentStep == 4 && empty($this->selectedProblems)) {
            session()->flash('error', 'Please select at least one problem.');
            return;
        }

        if ($this->currentStep < 7) {
            $this->currentStep++;
            $this->dispatch('scrollToTop');
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('scrollToTop');
        }
    }

    public function submitForm()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Calculate tax (10%)
            $subtotal = $this->formData['total_price'];
            $tax = $subtotal * 0.1;
            $total = $subtotal + $tax;

            // Create repair request
            $repairRequest = RepairRequest::create([
                'category_id' => $this->selectedCategory,
                'brand_id' => $this->selectedBrand,
                'model_id' => $this->selectedModel,
                'problems' => json_encode(array_values($this->formData['problems'])),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_price' => $total,
                'service_type' => $this->formData['type'],
                'preferred_date' => $this->formData['date'],
                'preferred_time' => $this->formData['time'],
                'first_name' => $this->formData['first_name'],
                'last_name' => $this->formData['last_name'],
                'email' => $this->formData['email'],
                'mobile' => $this->formData['mobile'],
                'imei' => $this->formData['imei'],
                'address' => $this->formData['address'],
                'zip_code' => $this->formData['zip_code'],
                'city' => $this->formData['city'],
                'state' => $this->formData['state'],
                'country' => $this->formData['country'],
                'message' => $this->formData['message'],
                'status' => 'pending',
            ]);

            DB::commit();

            session()->flash('success', 'Your repair request has been submitted successfully! We will contact you soon. Your request ID: #' . $repairRequest->id);

            // Reset form
            $this->reset(['selectedCategory', 'selectedBrand', 'selectedModel', 'selectedProblems', 'currentStep', 'formData', 'modelSearch', 'problemSearch']);
            $this->mount();

            $this->dispatch('scrollToTop');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Repair Request Submission Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong. Please try again or contact support.');
        }
    }

    public function getCategoryNameProperty()
    {
        if ($this->selectedCategory) {
            $category = Category::find($this->selectedCategory);
            return $category ? $category->name : '';
        }
        return '';
    }

    public function getBrandNameProperty()
    {
        if ($this->selectedBrand) {
            $brand = Brand::find($this->selectedBrand);
            return $brand ? $brand->name : '';
        }
        return '';
    }

    public function getModelNameProperty()
    {
        if ($this->selectedModel) {
            $model = BrandModel::find($this->selectedModel);
            return $model ? $model->name : '';
        }
        return '';
    }

    public function getFilteredModelsProperty()
    {
        $models = $this->models instanceof Collection
            ? $this->models
            : collect($this->models);

        if (empty($this->modelSearch)) {
            return $models;
        }

        return $models->filter(function ($model) {
            $model = (object) $model;
            return stripos($model->name ?? '', $this->modelSearch) !== false ||
                   stripos($model->model_number ?? '', $this->modelSearch) !== false;
        });
    }

    public function getFilteredProblemsProperty()
    {
        $problems = $this->problems instanceof Collection
            ? $this->problems
            : collect($this->problems);

        if (empty($this->problemSearch)) {
            return $problems;
        }

        return $problems->filter(function ($problem) {
            $problem = (object) $problem;
            return stripos($problem->name ?? '', $this->problemSearch) !== false ||
                   stripos($problem->description ?? '', $this->problemSearch) !== false;
        });
    }

    public function getTotalPriceProperty()
    {
        return $this->formData['total_price'] ?? 0;
    }

    public function render()
    {
        return view('livewire.user.repair');
    }
}
