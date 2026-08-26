  <div class="col-lg-3 page-nav">
    <div class="page-section pt-lg-112pt">
        <nav class="nav page-nav__menu">

            <a class="nav-link {{ request()->routeIs('admin.account') ? 'active' : '' }}"
               href="{{ route('admin.account') }}">
                Basic Information
            </a>

            <a class="nav-link {{ request()->routeIs('admin.account.edit-profile') ? 'active' : '' }}"
               href="{{ route('admin.account.edit-profile') }}">
                Profile &amp; Privacy
            </a>

            <a class="nav-link {{ request()->routeIs('admin.account.edit-password') ? 'active' : '' }}"
               href="{{ route('admin.account.edit-password') }}">
                Change Password
            </a>

        </nav>

        <div class="page-nav__content">
            <button type="submit" class="btn btn-accent">
                Save changes
            </button>
        </div>
    </div>
</div>