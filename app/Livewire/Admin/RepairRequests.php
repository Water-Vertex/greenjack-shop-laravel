<?php

namespace App\Livewire\Admin;

use App\Models\RepairRequest;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('components.admin-layout')]
class RepairRequests extends Component
{
    use WithPagination;
    
    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    public $showViewModal = false;
    public $viewingRequest = null;
    public $showStatusModal = false;
    public $selectedRequestId = null;
    public $selectedStatus = '';
    public $adminNotes = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10]
    ];
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function viewRequest($id)
    {
        try {
            $this->viewingRequest = RepairRequest::with(['category', 'brand', 'model'])->findOrFail($id);
            $this->showViewModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Request not found.');
        }
    }
    
    public function openStatusModal($id, $currentStatus, $notes = null)
    {
        $this->selectedRequestId = $id;
        $this->selectedStatus = $currentStatus;
        $this->adminNotes = $notes;
        $this->showStatusModal = true;
    }
    
    public function updateStatus()
    {
        try {
            $request = RepairRequest::findOrFail($this->selectedRequestId);
            $request->update([
                'status' => $this->selectedStatus,
                'admin_notes' => $this->adminNotes
            ]);
            
            session()->flash('success', 'Repair request status updated successfully!');
            $this->showStatusModal = false;
            $this->reset(['selectedRequestId', 'selectedStatus', 'adminNotes']);
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating status.');
        }
    }
    
    public function deleteRequest($id)
    {
        try {
            $request = RepairRequest::findOrFail($id);
            $request->delete();
            session()->flash('success', 'Repair request deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting repair request.');
        }
    }
    
    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    public function render()
    {
        $requests = RepairRequest::with(['category', 'brand', 'model'])
            ->when($this->search, function($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('mobile', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, function($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        
        $statusCounts = [
            'total' => RepairRequest::count(),
            'pending' => RepairRequest::where('status', 'pending')->count(),
            'confirmed' => RepairRequest::where('status', 'confirmed')->count(),
            'in_progress' => RepairRequest::where('status', 'in_progress')->count(),
            'completed' => RepairRequest::where('status', 'completed')->count(),
            'cancelled' => RepairRequest::where('status', 'cancelled')->count(),
        ];
        
        return view('livewire.admin.repair-requests', [
            'requests' => $requests,
            'statusCounts' => $statusCounts
        ]);
    }
}