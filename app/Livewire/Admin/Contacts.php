<?php

namespace App\Livewire\Admin;

use App\Models\Contact as ContactModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Contacts extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $showViewModal = false;
    public $viewingContact = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function clearFilters()
    {
        $this->search = '';
        $this->perPage = 10;
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewContact($id)
    {
        try {
            $this->viewingContact = ContactModel::findOrFail($id);
            $this->showViewModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Contact not found.');
        }
    }

    public function deleteContact($id)
    {
        try {
            $contact = ContactModel::find($id);
            
            if (!$contact) {
                session()->flash('error', 'Contact not found!');
                return;
            }
            
            $contact->delete();
            session()->flash('success', 'Contact deleted successfully!');
            $this->showViewModal = false;
            $this->viewingContact = null;
            $this->resetPage();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting contact: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $contacts = ContactModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.contacts', [
            'contacts' => $contacts,
            'totalContacts' => ContactModel::count(),
        ]);
    }
}