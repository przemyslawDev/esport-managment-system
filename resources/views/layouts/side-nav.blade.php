<nav class="navbar navbar-default navbar-fixed-side">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('dashboard') }}">{{ _('Dashboard') }}</a></li>
                @if(Auth::user()->hasRole('system_admin'))
                    @include('layouts.navs.system-admin')
                @elseif(Auth::user()->hasRole('admin'))
                    @include('layouts.navs.admin');
                @endif
            </ul>
        </div>
    </div>
</nav>