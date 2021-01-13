@include('backend.layout.head')
<body  data-layout-mode="horizontal">
    <div id="wrapper">
        @include('backend.layout.topbar')
        @include('backend.layout.horizontal')
        <div class="content-page pt-0 px-1">
            <div class="content">
                @yield('content')
            </div>
            @include('backend.layout.foot')
        </div>
    </div>
    <script src="{{asset('backend/js/vendor.min.js')}}"></script>
    @yield('script')
    
    <script src="{{asset('backend/js/app.min.js')}}"></script>
    @yield('script-bottom')
</body>
</html>