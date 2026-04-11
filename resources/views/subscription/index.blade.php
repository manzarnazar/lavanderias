@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow border-0 rounded-12">

                    {{-- Card Header --}}
                    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap" style="gap:8px">
                        <h2 class="card-title m-0">{{ __('Subscriptions') }}</h2>

                        <a class="btn btn-primary" data-toggle="modal" data-target="#createSubscriptionModal">
                            <i class="fa fa-plus"></i> {{ __('Add New Subscriptions') }}
                        </a>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="py-2">{{ __('SL') }}</th>
                                        <th class="py-2">{{ __('Title') }}</th>
                                        <th class="py-2">{{ __('Price') }}</th>
                                        <th class="py-2">{{ __('Type') }}</th>
                                        <th class="py-2">{{ __('Status') }}</th>
                                        <th class="py-2" style="min-width:130px">{{ __('Action') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($subscriptions as $subscription)
                                        <tr>
                                            <td class="py-2">{{ $loop->iteration }}</td>
                                            <td class="py-2">{{ $subscription->title }}</td>
                                            <td class="py-2">{{ $subscription->price }}</td>
                                            <td class="py-2">{{ $subscription->type }}</td>

                                            {{-- Status --}}
                                            <td class="py-2">
                                                <label class="switch">
                                                    <input type="checkbox" class="subscriptionStatus d-none"
                                                        data-id="{{ $subscription->id }}"
                                                        {{ $subscription->status->value == 'Active' ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            {{-- Action --}}
                                            <td class="py-2">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-primary" href="#" role="button"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="javascript:void(0)" class="dropdown-item"
                                                            data-toggle="modal"
                                                            data-target="#editSubscriptionModal_{{ $subscription->id }}">
                                                            <i class="fa fa-edit text-info"></i> {{ __('Edit') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- Edit Modal --}}
                                        <div id="editSubscriptionModal_{{ $subscription->id }}"
                                            class="modal fade text-left" tabindex="-1" data-backdrop="static">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('subscription.update', $subscription->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('Edit Subscription') }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label>{{ __('Title') }} <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="title"
                                                                        class="form-control"
                                                                        value="{{ $subscription->title }}"
                                                                        placeholder="{{ __('Enter Title') }}" required>
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label>{{ __('Price') }} <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" name="price"
                                                                        class="form-control"
                                                                        value="{{ $subscription->price }}"
                                                                        placeholder="{{ __('Enter Price') }}" required>
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label>{{ __('Type') }}</label>
                                                                    <select name="type" class="form-control">
                                                                        @foreach ($subscriptionTypes as $type)
                                                                            <option value="{{ $type->value }}"
                                                                                {{ $subscription->type->value == $type->value ? 'selected' : '' }}>
                                                                                {{ $type->value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6 mb-3">
                                                                    <label>{{ __('Status') }}</label>
                                                                    <select name="status" class="form-control">
                                                                        @foreach ($statuses as $status)
                                                                            <option value="{{ $status->value }}"
                                                                                {{ $subscription->status->value == $status->value ? 'selected' : '' }}>
                                                                                {{ $status->value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-12 mb-3">
                                                                    <label>{{ __('Description') }}</label>
                                                                    <textarea name="description" class="form-control" rows="4" placeholder="{{ __('Enter Description') }}">{{ $subscription->description }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ __('Close') }}</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">{{ __('Update') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="createSubscriptionModal" class="modal fade text-left" tabindex="-1" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="createSubscriptionForm">


                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('New Subscription') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>{{ __('Title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="{{ __('Enter Title') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>{{ __('Price') }} <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control"
                                    placeholder="{{ __('Enter Price') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>{{ __('Type') }}<span class="text-danger">*</span></label>
                                <select name="type" class="form-control" placeholder="{{ __('Select Type') }}">
                                    <option disabled selected>{{ __('Select Type') }}</option>
                                    @foreach ($subscriptionTypes as $type)
                                        <option value="{{ $type->value }}">{{ $type->value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>{{ __('Status') }}<span class="text-danger">*</span></label>
                                <select name="status" class="form-control" placeholder="{{ __('Select Status') }}">
                                    <option disabled selected>{{ __('Select Status') }}</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}">{{ $status->value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>{{ __('Description') }}</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="{{ __('Enter Description') }}"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Submit') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.subscriptionStatus').on("change", function() {
            const id = $(this).attr('data-id')
            const url = "{{ url('subscription/status-chanage/') }}";
            if ($(this).is(":checked")) {
                window.location.href = url + '/' + id + '/Active';
            } else {
                window.location.href = url + '/' + id + '/Inactive';
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            $(document).on('submit', '#createSubscriptionForm', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $('.text-danger.error-text').remove();

                $.ajax({
                    url: "{{ route('subscription.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            
                            $('#createSubscriptionModal').modal('hide');
                            $('#createSubscriptionForm')[0].reset();


                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message ||
                                    'Subscription created successfully!',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal
                                        .stopTimer)
                                    toast.addEventListener('mouseleave', Swal
                                        .resumeTimer)
                                }
                            });


                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('[name="' + key + '"]').after(
                                    '<span class="text-danger error-text">' + value[
                                        0] + '</span>'
                                );
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
