<div class="topnav mt-0">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link bg-amdbtn text-white" href="{{route('admin.home')}}">
                            <i class="fe-airplay mr-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bg-amdbtn text-white" href="{{route('admin.withdrawals.index')}}">
                            <i class="fe-download-cloud mr-1"></i> Withdrawals
                            @if (is_admin() || is_subadmin() || is_wd())                                
                                @if (count_withdrawal() > 0)
                                    <span class="badge badge-danger float-right blink-notif" style="margin-top: -5px">
                                        {{ number_format(count_withdrawal()) }}
                                    </span>
                                @endif
                            @endif
                        </a>
                    </li>
                    @if (is_admin() || is_subadmin() || is_wd())
                        @if (is_admin() || is_subadmin())
                            <li class="nav-item">
                                <a class="nav-link bg-amdbtn text-white" href="{{route('admin.banks.index')}}">
                                    <i class="fe-dollar-sign mr-1"></i> Banks
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-amdbtn text-white" href="{{route('admin.users.index')}}">
                                    <i class="fe-users mr-1"></i> Users
                                </a>
                            </li>                            
                        @endif
                        <li class="nav-item">
                            @if (is_wd())
                                <a class="nav-link bg-amdbtn text-white btn-suntikdana" href="#">
                                    <i class="fe-plus-circle mr-1"></i> Suntik Dana
                                </a>
                            @else
                                <a class="nav-link bg-amdbtn text-white" href="{{ route('admin.amounts.index') }}">
                                    <i class="fe-plus-circle mr-1"></i> Suntik Dana
                                    @if (count_amount() > 0)
                                        <span class="badge badge-danger float-right blink-notif" style="margin-top: -5px">
                                            {{ number_format(count_amount()) }}
                                        </span>
                                    @endif
                                </a>
                            @endif
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bg-amdbtn text-white" href="{{ route('admin.reports.index') }}">
                                <i class="fe-book mr-1"></i> Laporan
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link bg-amdbtn text-white" href="{{route('admin.users.profile')}}">
                            <i class="fe-user mr-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bg-amdbtn text-white" href="{{route('logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="fe-log-out mr-1"></i> Logout
                        </a>
                        <form id="logout-form" class="d-none" action="{{ route('logout') }}" method="POST" >
                            @csrf
                        </form>
                    </li>
                </ul> <!-- end navbar-->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link bg-amdbtn text-white" href="{{route('admin.users.profile')}}">
                            @if (is_admin())
                                TEAM ADMIN                            
                            @endif
                            @if (is_cs())
                                TEAM CS                            
                            @endif
                            @if (is_wd())
                                TEAM WITHDRAW                            
                            @endif
                        </a>
                    </li>
                </ul>
            </div> <!-- end .collapsed-->
        </nav>
    </div> <!-- end container-fluid -->
</div> <!-- end topnav-->
@if (is_admin() || is_subadmin() || is_wd())    
    @section('script-bottom')
    <script>
        function blink_text() {
            $('.blink-notif').fadeOut(500);
            $('.blink-notif').fadeIn(500);
        }
        setInterval(blink_text, 1000);
    </script>
    @endsection
@endif