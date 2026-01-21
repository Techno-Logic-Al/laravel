@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="fs-3">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                </div>
                <div class="card-body">
                    <div class="row align-items-start mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    @if ($employee->avatar)
                                        <img src="{{ asset('storage/'.$employee->avatar) }}"
                                             alt="{{ $employee->first_name }} {{ $employee->last_name }} avatar"
                                             class="rounded-circle employee-avatar-img"
                                             style="width:100px;height:100px;object-fit:cover;">
                                    @else
                                        <img src="{{ asset('images/employee-placeholder.jpg') }}"
                                             alt="Employee avatar placeholder"
                                             class="rounded-circle employee-placeholder-img employee-avatar-img"
                                             style="width:100px;height:100px;object-fit:cover;">
                                    @endif
                                </div>
                                <div class="text-white">
                                    <div class="mb-1">
                                        Employee ID: {{ $employee->id }}
                                    </div>
                                    <div class="mb-1">
                                        Gender: {{ $employee->gender }}
                                    </div>
                                    <div class="mb-1">
                                        Email:
                                        @if ($employee->email)
                                            <a href="mailto:{{ $employee->email }}" class="text-white text-decoration-none company-contact-link">
                                                {{ $employee->email }}
                                            </a>
                                        @else
                                            <span class="text-white">Not provided</span>
                                        @endif
                                    </div>
                                    <div class="mb-1">
                                        Phone:
                                        @if ($employee->phone)
                                            <span>{{ $employee->phone }}</span>
                                        @else
                                            <span class="text-white">Not provided</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @php
                                $company = $employee->company;
                            @endphp
                            @if ($company)
                                <div class="d-flex align-items-center">
                                    <div class="me-4">
                                        <a href="{{ route('companies.show', $company) }}" class="d-inline-block text-decoration-none">
                                            @if ($company->logo)
                                                <img src="{{ asset('storage/'.$company->logo) }}"
                                                     alt="{{ $company->name }} logo"
                                                     class="company-logo-img"
                                                     style="width:100px;height:100px;object-fit:cover;">
                                            @else
                                                <img src="{{ asset('images/company-placeholder.png') }}"
                                                     alt="Placeholder logo for {{ $company->name }}"
                                                     class="company-logo-img"
                                                     style="width:100px;height:100px;object-fit:cover;">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="text-white">
                                        <div class="mb-1 fs-3">
                                            <a href="{{ route('companies.show', $company) }}" class="text-white text-decoration-none company-contact-link">
                                                {{ $company->name }}
                                            </a>
                                        </div>
                                        <div class="mb-1">
                                            Email:
                                            @if ($company->email)
                                                <a href="mailto:{{ $company->email }}" class="text-white text-decoration-none company-contact-link">
                                                    {{ $company->email }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                        <div class="mb-1">
                                            Website:
                                            @if ($company->website)
                                                <a href="{{ $company->website }}" target="_blank" class="text-white text-decoration-none company-contact-link">
                                                    {{ $company->website }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-muted">
                                    No company information available for this employee.
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-start flex-wrap gap-3 mt-3">
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">Edit Employee</a>
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this employee?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Employee</button>
                        </form>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">BACK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
