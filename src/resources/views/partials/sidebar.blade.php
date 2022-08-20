<div class="sidebar" data-color="yellow">
    <!--
          Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
      -->
    <div class="logo">
        <a href="" class="simple-text logo-mini">
            <img src="{{asset('images/defaults/logo.png')}}"/>
        </a>

        <a href="" class="simple-text logo-normal">
            {{env("APP_NAME")}}
        </a>
        <div class="navbar-minimize">
            <button id="minimizeSidebar" class="btn btn-simple btn-icon btn-neutral btn-round">
                <i class="now-ui-icons text_align-center visible-on-sidebar-regular"></i>
                <i class="now-ui-icons design_bullet-list-67 visible-on-sidebar-mini"></i>
            </button>
        </div>

    </div>
    <div class="sidebar-wrapper" id="sidebar-wrapper">
        {{-- @auth --}}
        <ul class="nav">
            <li class="{{ (request()->segment(1) == 'home') ? 'active' : '' }}">
                <a href="{{route('home')}}">
                    <i class="now-ui-icons design_app"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'announcements') ? 'active' : '' }}">
                <a href="{{route('announcements')}}">
                    <i class="now-ui-icons business_bulb-63"></i>
                    <p>Announcements</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'users') ? 'active' : '' }}">
                <a href="{{url('/users')}}">
                    <i class="now-ui-icons users_single-02"></i>
                    <p>Users Management</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'orders') ? 'active' : '' }}">
                <a href="{{url('/orders')}}">
                    <i class="now-ui-icons shopping_basket"></i>
                    <p>Orders</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'offers') ? 'active' : '' }}">
                <a href="{{url('/offers')}}">
                    <i class="now-ui-icons ui-1_email-85"></i>
                    <p>Offers</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'counter-offers') ? 'active' : '' }}">
                <a href="{{url('/counter-offers')}}">
                    <i class="now-ui-icons now-ui-icons files_box"></i>
                    <p>Counter Offers</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'trips') ? 'active' : '' }}">
                <a href="{{url('/trips')}}">
                    <i class="now-ui-icons transportation_air-baloon"></i>
                    <p>Trips</p>
                </a>
            </li>
            <li>
                <hr>
            </li>
            <li class="{{ (request()->segment(1) == 'payments') ? 'active' : '' }}">
                <a href="{{url('/payments')}}">
                    <i class="now-ui-icons business_money-coins"></i>
                    <p>Payments</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'transactions') ? 'active' : '' }}">
                <a href="{{url('/transactions')}}">
                    <i class="now-ui-icons business_money-coins"></i>
                    <p>Transactions</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'disputes') ? 'active' : '' }}">
                <a href="{{url('/disputes')}}">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Disputes</p>
                </a>
            </li>
{{--            <li class="{{ (request()->segment(1) == 'reports') ? 'active' : '' }}">--}}
{{--                <a href="{{url('/reports')}}">--}}
{{--                    <i class="now-ui-icons files_paper"></i>--}}
{{--                    <p>Reports</p>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="{{ (request()->segment(1) == 'reference-list') ? 'active' : '' }}">
                <a href="{{route('referenceList')}}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Reference Lists</p>
                </a>
            </li>
            <li class="{{ (request()->segment(1) == 'settings') ? 'active' : '' }}">
                <a href="{{route('settings')}}">
                    <i class="now-ui-icons loader_gear"></i>
                    <p>System Setting</p>
                </a>
            </li>
        </ul>
        {{-- @endauth --}}
    </div>
</div>
