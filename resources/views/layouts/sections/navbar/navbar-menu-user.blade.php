@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
@endphp
<ul class="navbar-nav flex-row align-items-center ms-auto">

    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt class="rounded-circle">
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item mt-0" href="{{ Route::has('profile.show') ? route('profile.show') : 'javascript:void(0);' }}">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-2">
                <div class="avatar avatar-online">
                  <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt class="rounded-circle">
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-0">
                  @if (Auth::check())
                    {{ Auth::user()->name }}
                  @else
                    John Doe
                  @endif
                </h6>
                <div>
                @php
                    echo Auth::user()->roles ? Auth::user()->roles->pluck('name')->map(function($role) {
                        return '<small class="text-muted"><span class="badge bg-label-primary">' . $role . '</span></small>';
                    })->join(' ') : '';
                @endphp
                </div>
              </div>
            </div>
          </a>
        </li>
        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        <li>
          <a class="dropdown-item" href="{{ Route::has('admin') ? route('admin') : 'javascript:void(0);' }}">
            <i class="ti ti-home me-3 ti-md"></i><span class="align-middle">Home</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="{{ Route::has('profile.show') ? route('profile.show') : 'javascript:void(0);' }}">
            <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">Profil Saya</span>
          </a>
        </li>

        @if (Auth::check() && Laravel\Jetstream\Jetstream::hasApiFeatures())
          <li>
            <a class="dropdown-item" href="{{ route('api-tokens.index') }}">
              <i class="ti ti-key ti-md me-3"></i><span class="align-middle">API Tokens</span>
            </a>
          </li>
        @endif
        {{-- <li>
          <a class="dropdown-item" href="javascript:void(0);">
            <span class="d-flex align-items-center align-middle">
              <i class="flex-shrink-0 ti ti-file-dollar me-3 ti-md"></i><span class="flex-grow-1 align-middle">Billing</span>
              <span class="flex-shrink-0 badge bg-danger d-flex align-items-center justify-content-center">4</span>
            </span>
          </a>
        </li> --}}

        @if (Auth::User() && Laravel\Jetstream\Jetstream::hasTeamFeatures())
          <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
          </li>
          <li>
            <h6 class="dropdown-header">Manage Team</h6>
          </li>
          <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{ Auth::user() ? route('teams.show', Auth::user()->currentTeam->id) : 'javascript:void(0)' }}">
              <i class="ti ti-settings ti-md me-3"></i><span class="align-middle">Team Settings</span>
            </a>
          </li>
          @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
            <li>
              <a class="dropdown-item" href="{{ route('teams.create') }}">
                <i class="ti ti-user ti-md me-3"></i><span class="align-middle">Create New Team</span>
              </a>
            </li>
          @endcan

          @if (Auth::user()->allTeams()->count() > 1)
            <li>
              <div class="dropdown-divider my-1 mx-n2"></div>
            </li>
            <li>
              <h6 class="dropdown-header">Switch Teams</h6>
            </li>
            <li>
              <div class="dropdown-divider my-1 mx-n2"></div>
            </li>
          @endif

          @if (Auth::user())
            @foreach (Auth::user()->allTeams() as $team)
            {{-- Below commented code read by artisan command while installing jetstream. !! Do not remove if you want to use jetstream. --}}

            <x-switchable-team :team="$team" />
            @endforeach
          @endif
        @endif
        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        @if (Auth::check())
          <li>
            <div class="d-grid px-2 pt-2 pb-1">
              <a class="btn btn-sm btn-danger d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <small class="align-middle">Logout</small>
                <i class="ti ti-logout ms-2 ti-14px"></i>
              </a>
            </div>
          </li>
          <form method="POST" id="logout-form" action="{{ route('logout') }}">
            @csrf
          </form>
        @else
          <li>
            <div class="d-grid px-2 pt-2 pb-1">
              <a class="btn btn-sm btn-danger d-flex" href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                <small class="align-middle">Login</small>
                <i class="ti ti-login ms-2 ti-14px"></i>
              </a>
            </div>
          </li>
        @endif
      </ul>
    </li>
    <!--/ User -->
  </ul>
