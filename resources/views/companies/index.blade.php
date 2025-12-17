@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $currentSort = request('sort');
        $currentDirection = request('direction', 'asc');
        $nextDirection = function (string $column) use ($currentSort, $currentDirection): string {
            return $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
        };
        $arrowFor = function (string $column) use ($currentSort, $currentDirection): string {
            if ($currentSort !== $column) {
                return '';
            }
            return $currentDirection === 'asc' ? '▲' : '▼';
        };
    @endphp
    @php
        // Override sort arrow symbols with clear up/down triangles
        $arrowFor = function (string $column) use ($currentSort, $currentDirection): string {
            if ($currentSort !== $column) {
                return '';
            }
            return $currentDirection === 'asc' ? '▲' : '▼';
        };
    @endphp
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Companies</span>
                    <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm">Add Company</a>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-white">Maintain your company directory, including contact details, websites, and branded logos.</p>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="alert alert-success mb-0 text-center">
                    {{ session('status') }}
                </div>
            </div>
        </div>
    @endif

    @if ($companies->count())
        <div class="card">
            <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:60px"></th>
                    <th>
                        <a href="{{ route('companies.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => $nextDirection('name')])) }}"
                           class="table-sort-link">
                            Name
                            @if ($arrowFor('name'))
                                <span>{{ $arrowFor('name') }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('companies.index', array_merge(request()->query(), ['sort' => 'employees', 'direction' => $nextDirection('employees')])) }}"
                           class="table-sort-link">
                            Employees
                            @if ($arrowFor('employees'))
                                <span>{{ $arrowFor('employees') }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('companies.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => $nextDirection('email')])) }}"
                           class="table-sort-link">
                            Email
                            @if ($arrowFor('email'))
                                <span>{{ $arrowFor('email') }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('companies.index', array_merge(request()->query(), ['sort' => 'website', 'direction' => $nextDirection('website')])) }}"
                           class="table-sort-link">
                            Website
                            @if ($arrowFor('website'))
                                <span>{{ $arrowFor('website') }}</span>
                            @endif
                        </a>
                    </th>
                    <th style="width:140px; white-space:nowrap">
                        <a href="{{ route('companies.index', array_merge(request()->query(), ['sort' => 'updated_at', 'direction' => $nextDirection('updated_at')])) }}"
                           class="table-sort-link">
                            Last Updated
                            @if ($arrowFor('updated_at'))
                                <span>{{ $arrowFor('updated_at') }}</span>
                            @endif
                        </a>
                    </th>
                    <th width="240">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                    <tr>
                        <td>
                            @if ($company->logo)
                                <a href="{{ route('companies.show', $company) }}" class="d-block">
                                    <img src="{{ asset('storage/'.$company->logo) }}"
                                         alt="{{ $company->name }} logo"
                                         class="company-logo-img"
                                         style="width:40px;height:40px;object-fit:cover;">
                                </a>
                            @else
                                <a href="{{ route('companies.show', $company) }}" class="d-block">
                                    <img src="{{ asset('images/company-placeholder.png') }}"
                                         alt="Placeholder logo for {{ $company->name }}"
                                         class="company-logo-img"
                                         style="width:40px;height:40px;object-fit:cover;">
                                </a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('companies.show', $company) }}" class="d-block text-reset text-decoration-none">
                                {{ $company->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('companies.show', $company) }}" class="d-block text-reset text-decoration-none">
                                {{ $company->employees_count }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('companies.show', $company) }}" class="d-block text-reset text-decoration-none">
                                {{ $company->email }}
                            </a>
                        </td>
                        <td>
                            @if ($company->website)
                                <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('companies.show', $company) }}" class="d-block text-reset text-decoration-none">
                                {{ optional($company->updated_at)->format('d M Y') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                            <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this company?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            </div>
        </div>

        <div class="mt-4" id="pagination">
            {{ $companies->links() }}
        </div>
    @else
        <p>No companies found.</p>
    @endif
</div>
@endsection
