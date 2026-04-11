<nav class="bg-white py-4 sticky-top shadow-sm py-4 px-5">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <!-- Left side: Dashboard title + breadcrumb -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
            <h6 class="h2 d-inline-block me-3">{{ __('Dashboard') }}</h6>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('root') }}"><i class="fa fa-home text-primary"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Dashboard') }}</li>
                </ol>
            </nav>
        </div>

        <!-- Right side: Language selector + Profile -->
        @php
            use App\Models\Language;
            $languages = Language::All();
        @endphp
        <div class="d-flex align-items-center flex-wrap mt-3 mt-md-0 gap-3">

            <!-- Notification -->
            <div class="position-relative me-4">
                <a href="#"
                    class="d-flex align-items-center gap-2 text-decoration-none px-3 py-2
              rounded-pill border border-2 border-secondary position-relative"
                    style="background-color: #ffffff;">

                    <!-- Bell Icon -->
                    <i class="fas fa-bell text-primary"></i>

                    <!-- Text -->
                    <span class="fw-semibold text-dark small">Notifications</span>

                </a>
            </div>
            <!-- Language Selector -->
            <div class="local d-flex align-items-center">

                <select id="language" name="ln" class="form-control">
                    <option value="">{{ __('Select_Language') }}</option>
                    @foreach ($languages as $language)
                        <option value="{{ $language->name }}"
                            {{ session()->get('local') == $language->name ? 'selected' : '' }}>
                            {{ __($language->title) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Profile -->
            <div class="profile d-flex align-items-center">
                <div class="px-2">
                    <div class="d-flex justify-content-end">
                        <h3 class="name m-0">{{ auth()->user()->name }}</h3>
                    </div>
                    <div class="d-flex justify-content-end">
                        <p class="email m-0">{{ auth()->user()->email }}</p>
                    </div>

                </div>

                <div class="position-relative">

                    <img src="{{ auth()->user()->profile_photo_path }}" alt="" width="50" height="50"
                        class="rounded-circle" id="profileToggle" style="cursor:pointer;">


                    <div id="profileDropdown" class="tp-header-author-thumb-more position-absolute"
                        style="display:none; background: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-width: 250px; top: 60px; right: 0; z-index: 999;">

                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user text-primary"></i>
                            <span class="nav-link-text">{{ __('Profile') }}</span>
                        </a>
                        <a class="nav-link" onclick="event.preventDefault(); document.getElementById('logout').submit()"
                            href="#">
                            <i class="fas fa-sign-out-alt text-warning"></i>
                            <span class="nav-link-text">{{ __('Logout') }}</span>
                        </a>
                        <form id="logout" action="{{ route('logout') }}" method="POST"> @csrf </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    const toggle = document.getElementById('profileToggle');
    const dropdown = document.getElementById('profileDropdown');

    toggle.addEventListener('click', () => {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });


    document.addEventListener('click', (e) => {
        if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>
