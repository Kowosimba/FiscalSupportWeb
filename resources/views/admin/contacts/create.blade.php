@extends('layouts.tickets')

@section('ticket-content')
<div class="dashboard-nav-wrapper mb-4">
    {{-- ...navigation as above... --}}
</div>
<div class="container-fluid">
    <div class="page-header-card mb-4">
        <div class="page-header-content">
            <h3 class="page-title">
                <i class="fa fa-user-plus me-2"></i>
                {{ isset($contact) ? 'Edit Contact' : 'Add New Contact' }}
            </h3>
        </div>
    </div>
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="card-title">
                <i class="fa fa-id-card me-2"></i>
                {{ isset($contact) ? 'Edit Contact' : 'Add Contact' }}
            </h5>
        </div>
        <div class="content-card-body">
            <form action="{{ isset($contact) ? route('admin.contacts.update', $contact) : route('admin.contacts.store') }}" method="POST">
                @csrf
                @if(isset($contact)) @method('PUT') @endif
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control form-control-enhanced" required value="{{ old('name', $contact->name ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form-control-enhanced" value="{{ old('email', $contact->email ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control form-control-enhanced" value="{{ old('phone', $contact->phone ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" class="form-control form-control-enhanced" value="{{ old('company', $contact->company ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Position</label>
                    <input type="text" name="position" class="form-control form-control-enhanced" value="{{ old('position', $contact->position ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control form-control-enhanced" rows="3">{{ old('notes', $contact->notes ?? '') }}</textarea>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $contact->is_active ?? true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Active</label>
                </div>
                <div class="form-actions d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-enhanced">
                        <i class="fa fa-save me-2"></i> {{ isset($contact) ? 'Update Contact' : 'Save Contact' }}
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-enhanced">
                        <i class="fa fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
