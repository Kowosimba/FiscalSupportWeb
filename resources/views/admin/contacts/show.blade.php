@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Contact Details</h3>
                    <div>
                        <a href="{{ route('admin.contacts.edit', $contact) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Name:</td>
                                    <td>{{ $contact->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td>
                                        @if($contact->email)
                                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Phone:</td>
                                    <td>
                                        @if($contact->phone)
                                            <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Company:</td>
                                    <td>{{ $contact->company ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Position:</td>
                                    <td>{{ $contact->position ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        @if($contact->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Notes:</label>
                                <div class="mt-2">
                                    @if($contact->notes)
                                        <div class="border p-3 rounded bg-light">
                                            {{ $contact->notes }}
                                        </div>
                                    @else
                                        <span class="text-muted">No notes available</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Created:</label>
                                <div>{{ $contact->created_at->format('M d, Y \a\t H:i') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Last Updated:</label>
                                <div>{{ $contact->updated_at->format('M d, Y \a\t H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection