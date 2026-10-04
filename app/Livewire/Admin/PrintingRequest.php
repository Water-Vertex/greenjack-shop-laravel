<?php

namespace App\Livewire\Admin;

use App\Models\PrintingRequest as PrintingRequestModel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

#[Layout('components.admin-layout')]
class PrintingRequest extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortField = 'created_at';

    #[Url(history: true)]
    public $sortDirection = 'desc';

    public $showViewModal = false;
    public $viewingRequest = null;

    protected $queryString = ['search', 'perPage', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->perPage = 10;
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }

    public function viewRequest($id)
    {
        $this->viewingRequest = PrintingRequestModel::findOrFail($id);
        $this->showViewModal = true;
    }

    public function deleteRequest($id)
    {
        try {
            $request = PrintingRequestModel::findOrFail($id);
            $request->delete();

            session()->flash('success', 'Printing request deleted successfully!');

            // Close modal if open
            $this->showViewModal = false;
            $this->viewingRequest = null;

            // Refresh the list
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete printing request.');
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $request = PrintingRequestModel::findOrFail($id);
            $request->status = $status;
            $request->save();

            session()->flash('success', 'Status updated successfully!');

            // Refresh viewing request if modal is open
            if ($this->showViewModal && $this->viewingRequest && $this->viewingRequest->id == $id) {
                $this->viewingRequest = $request;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update status.');
        }
    }

    public function render()
    {
        $requests = PrintingRequestModel::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('service', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.printing-request', [
            'requests' => $requests,
            'totalRequests' => PrintingRequestModel::count()
        ]);
    }
}
