<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <div class="navbar-brand">
        <button class="btn-menu ls-toggle-btn" type="button"><i class="zmdi zmdi-menu"></i></button>
        <a href="{{route('cms.dashboard')}}"><img src="{{ asset('/assets/images/logo.svg') }}" width="25" alt="Aero"><span class="m-l-10">CMS Klik</span></a>
    </div>
    <div class="menu">
        <ul class="list">
            <li>
                <div class="user-info">
                    <div class="image"><a href="#"><img src="{{ asset('/assets/images/profile_av.jpg') }}" alt="User"></a></div>
                    <div class="detail">
                        <h4>{{ Auth::user()->name }}</h4>
                        @foreach(Auth::user()->roles->pluck('name') as $name)
                            <small>{{ $name }}</small>
                        @endforeach
                    </div>
                </div>
            </li>            
            <li class="{{ Request::segment(1) === 'dashboard' ? 'active open' : null }}"><a href="{{ route('cms.dashboard') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>
            <li>
                <a href="#Project" class="menu-toggle"><i class="zmdi zmdi-assignment"></i> <span>Master</span></a>
                <ul class="ml-menu">
                    <li><a href="{{route('roles.index')}}">Roles</a></li>
                    <li><a href="{{route('topics.index')}}">Topic</a></li>
                    <li><a href="{{route('list.index')}}">Problem List</a></li>
                    <li><a href="{{route('faq.index')}}">FAQ</a></li>
                </ul>
            </li>
            <li class="{{ Request::segment(1) === 'users' ? 'active open' : null }}"><a href="{{ route('users.index') }}"><i class="zmdi zmdi-account"></i><span>User</span></a></li>
        </ul>
    </div>
</aside>
