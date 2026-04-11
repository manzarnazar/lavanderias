@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="col-sm-6 p-md-0  mt-2 mt-sm-0 d-flex">
            <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i>
                {{ __('Back') }}
            </a>
        </div>
        <div class="row">
            <div class="col-xl-12 col-xxl-12 col-lg-12 mt-4">
                <div class="card shadow border-0 rounded-12">
                    <div class="card-header bg-primary py-3">
                        <h3 class="card-title m-0 text-white">{{ __('Faqs') }}</h3>
                    </div>

                    <div class="card-body ">
                        @foreach ($faqs as $faq)
                            <form action="{{ route('faq.update', $faq->slug) }}" type="Save And Update" method="post">
                                @csrf

                                <div class="row">
                                    <x-input name='slug' type="hidden" placeholder="Slug" value="{{ $faq->slug }}" />
                                    <div class="mb-3 col-12">
                                        <h3>{{ $faq->slug }}</h3>
                                        <div id="{{ $faq->slug }}" class="row">

                                            @php
                                                $faqs = json_decode($faq->content);

                                            @endphp
                                            @foreach ($faqs as $faqData)
                                                @foreach ($faqData as $i => $f)
                                                    <div class="col-4 mb-3"
                                                        id="faq-item-{{ $faq->slug }}-{{ $i }}">
                                                        <div class="border rounded p-2">
                                                            <x-input name='ques[]' type="text"
                                                                placeholder="Type the question"
                                                                value="{{ $f->ques }}" />
                                                            <textarea class="form-control" name="answer[]" placeholder="Type the answer">{{ $f->answer }}</textarea>
                                                        </div>
                                                        <div>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger remove-accordian mt-2 w-100"
                                                                onclick="removeFAQ('faq-item-{{ $faq->slug }}-{{ $i }}')">{{ __('Remove') }}</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-primary btn-sm "
                                                id="add-{{ $faq->slug }}">Add
                                                more</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>

                            </form>
                        @endforeach
                    </div>

                </div>




            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        $('#name').keyup(function() {
            $('#slug').val($(this).val().toLowerCase().split(',').join('').replace(/\s/g, "-"));
        });
    </script>

    <script>
        document.getElementById('add-phone').addEventListener('click', function() {
            const wrapper = document.getElementById('phone-wrapper');

            const div = document.createElement('div');
            div.classList.add('col-12', 'mb-2', 'phone-item');

            div.innerHTML = `
                <div class="input-group">
                    <input type="text" class="form-control" name="phone_no[]" required placeholder="Enter another phone number">
                    <button type="button" class="btn btn-danger remove-phone" style="height:39px">✕</button>
                </div>
            `;

            wrapper.appendChild(div);
        });

        // Remove phone field
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-phone')) {
                e.target.closest('.phone-item').remove();
            }
        });


        //email
        document.getElementById('add-email').addEventListener('click', function() {
            const wrapper = document.getElementById('email-wrapper');

            const div = document.createElement('div');
            div.classList.add('col-12', 'mb-2', 'email-item');

            div.innerHTML = `
                <div class="input-group">
                    <input type="email" class="form-control" name="email[]" required placeholder="Enter another email address">
                    <button type="button" class="btn btn-danger remove-email" style="height:39px">✕</button>
                </div>
            `;

            wrapper.appendChild(div);
        });

        // Remove email field
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-email')) {
                e.target.closest('.email-item').remove();
            }
        });


        //business
        document.getElementById('add-business').addEventListener('click', function() {
            const wrapper = document.getElementById('business-wrapper');

            const div = document.createElement('div');
            div.classList.add('col-12', 'mb-2', 'business-item');

            div.innerHTML = `
                <div class="input-group">
                    <input type="text" class="form-control" name="business[]" required placeholder="Enter another business hour">
                    <button type="button" class="btn btn-danger remove-business" style="height:39px">✕</button>
                </div>
            `;

            wrapper.appendChild(div);
        });

        // Remove business field
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-business')) {
                e.target.closest('.business-item').remove();
            }
        });
    </script>

    <script>

        document.querySelectorAll('[id^="add-"]').forEach(button => {
            button.addEventListener('click', function() {
                let faqContainer = document.getElementById(this.id.replace('add-', ''));
                let index = faqContainer.children.length;

                let newFAQ = document.createElement('div');
                newFAQ.classList.add('col-4', 'mb-4');
                newFAQ.id = 'faq-item-' + faqContainer.id + '-' + index;

                newFAQ.innerHTML = `
                <div class="border rounded p-2">
                    <x-input name="ques[]" type="text" placeholder="Type the question" />
                    <textarea class="form-control" name="answer[]" placeholder="Type the answer"></textarea>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-accordian mt-2 w-100" onclick="removeFAQ('faq-item-${faqContainer.id}-${index}')">
                        Remove
                    </button>
                </div>
            `;

                faqContainer.appendChild(newFAQ);
            });
        });

        function removeFAQ(faqId) {
            let faqItem = document.getElementById(faqId);
            faqItem.remove();
        }
    </script>
@endpush
