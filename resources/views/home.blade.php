@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white fw-bold">
                            Welcome {{ Auth::user()->name ?? Auth::user()->email }}!
                        </span>
                        <span class="text-white text-end ms-3">
                            <strong>admin&lt;[station]</strong> currently has a total of
                            <span class="fw-semibold">{{ $employeeCount }}</span>
                            <strong>{{ \Illuminate\Support\Str::plural('employee', $employeeCount) }}</strong>
                            registered to
                            <span class="fw-semibold">{{ $companyCount }}</span>
                            <strong>{{ \Illuminate\Support\Str::plural('company', $companyCount) }}</strong>.
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Companies</span>
                    <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm">Add Company</a>
                </div>
                <div class="card-body">
                    @if($recentCompanies->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-3">
                                <thead>
                                    <tr>
                                        <th style="width:60px"></th>
                                        <th>Recently Added</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCompanies as $company)
                                        <tr onclick="window.location='{{ route('companies.show', $company) }}'" style="cursor:pointer;">
                                            <td>
                                                @if ($company->logo)
                                                    <img src="{{ route('media', ['path' => $company->logo]) }}"
                                                     alt="{{ $company->name }} logo"
                                                     class="company-logo-img"
                                                     style="width:40px;height:40px;object-fit:cover;">
                                                @else
                                                    <img src="{{ asset('images/company-placeholder.png') }}"
                                                         alt="Placeholder logo for {{ $company->name }}"
                                                         class="company-logo-img"
                                                         style="width:40px;height:40px;object-fit:cover;">
                                                @endif
                                            </td>
                                            <td>{{ $company->name }}</td>
                                            <td>{{ optional($company->created_at)->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-3 text-muted">No companies have been added yet.</p>
                    @endif
                    <div class="d-flex justify-content-end mt-2">
                        <a href="{{ route('companies.index') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Employees</span>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">Add Employee</a>
                </div>
                <div class="card-body">
                    @if($recentEmployees->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-3">
                                <thead>
                                    <tr>
                                        <th style="width:60px"></th>
                                        <th>Recently Added</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEmployees as $employee)
                                        <tr onclick="window.location='{{ route('employees.show', $employee) }}'" style="cursor:pointer;">
                                            <td>
                                                @if ($employee->avatar)
                                                    <img src="{{ route('media', ['path' => $employee->avatar]) }}"
                                                     alt="{{ $employee->first_name }} {{ $employee->last_name }} avatar"
                                                     class="rounded-circle employee-avatar-img"
                                                     style="width:40px;height:40px;object-fit:cover;">
                                                @else
                                                    <img src="{{ asset('images/employee-placeholder.jpg') }}"
                                                         alt="Employee avatar placeholder"
                                                         class="rounded-circle employee-placeholder-img employee-avatar-img"
                                                         style="width:40px;height:40px;object-fit:cover;">
                                                @endif
                                            </td>
                                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                            <td>{{ optional($employee->created_at)->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-3 text-muted">No employees have been added yet.</p>
                    @endif
                    <div class="d-flex justify-content-end mt-2">
                        <a href="{{ route('employees.index') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
