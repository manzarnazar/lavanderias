@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow border-0 rounded-12">
                    <div class="card-header d-flex align-items-center py-3 justify-content-between">
                        <h2 class="card-title m-0">Commission History</h2>
                        <form method="GET" action="{{ route('history-commissions') }}">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <label for="store_id">Select Store</label>
                                    <select name="store_id" class="form-control" style="height:43px">
                                        <option value="">All Stores</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}"
                                                {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="from_date">Start Date</label>
                                    <input type="date" name="from_date" class="form-control"
                                        value="{{ request('from_date') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="to_date">End Date</label>
                                    <input type="date" name="to_date" class="form-control"
                                        value="{{ request('to_date') }}">
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-info">Filter</button>
                                    <a href="{{ route('history-commissions') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>


                    </div>

                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped {{ session()->get('local') }}" id="myTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Store</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $index => $transaction)
                                        <tr>
                                            <td>{{ $transactions->firstItem() + $index }}</td>
                                            <td>{{ optional($transaction->store)->name ?? 'N/A' }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $transaction->type === 'credit' ? 'bg-success' : 'bg-danger' }}">
                                                    Credit
                                                </span>
                                            </td>
                                            <td>৳ {{ number_format($transaction->amount, 2) }}</td>
                                            <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-3">No commission history found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($transactions->hasPages())
                        <div class="card-footer">{{ $transactions->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        td {
            padding: 5px 10px !important;
        }
    </style>
@endsection
