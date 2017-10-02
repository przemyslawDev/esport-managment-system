<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul id="side-menu" class="nav in">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard fa-fw"></i>
                    {{ _('Dashboard') }}
                </a>
            </li>
            @if(Auth::user()->hasRole('system_admin'))
                @include('layouts.side-navs.system-admin')
            @elseif(Auth::user()->hasRole('admin'))
                @include('layouts.side-navs.admin');
            @elseif(Auth::user()->hasRole('manager'))
                @include('layouts.side-navs.manager')
            @endif
        </ul>
    </div>
</div>