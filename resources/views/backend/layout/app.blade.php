@include('backend.layout.head')
<body  data-layout-mode="horizontal">
    <div id="wrapper">
        @include('backend.layout.topbar')
        @include('backend.layout.horizontal')
        <div class="content-page pt-0 px-1">
            <div class="content">
                @yield('content')
            </div>
            <div class="modal fade" id="suntikdana" tabindex="-1" role="dialog" aria-labelledby="suntikdanaLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <form class="formsuntikdana" method="POST" action="{{ route('admin.banks.suntikdana') }}">
                        @csrf
                        <div class="modal-header">
                          <h5 class="modal-title d-block w-100 text-center" id="suntikdanaLabel">Suntik Dana</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Select Bank</label>
                                <select name="banks" class="custom-select" data-toggle="select2">
                                    <option value="0">Pilih Bank</option>
                                    @foreach ($banks as $item)
                                        <option value="{{ $item->id }}">{{ $item->name.' - '.$item->bankname  }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="saldo">Nominal</label>
                                <input type="text" class="form-control" id="saldo"  name="saldo"  placeholder="5.000.000" data-toggle="input-mask" data-mask-format="000.000.000.000.000" data-reverse="true" >
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </form>
                  </div>
                </div>
            </div>
            @include('backend.layout.foot')
        </div>
    </div>
    <script src="{{asset('backend/js/vendor.min.js')}}"></script>
    <script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
    <script src="{{asset('backend/libs/jquery-mask-plugin/jquery-mask-plugin.min.js')}}"></script>
    <script src="{{asset('backend/libs/jquery-toast-plugin/jquery-toast-plugin.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('select[data-toggle="select2"]').select2();
            $('[data-toggle="input-mask"]').each(function (idx, obj) {
                var maskFormat = $(obj).data("maskFormat");
                var reverse = $(obj).data("reverse");
                if (reverse != null)
                {
                    $(obj).mask(maskFormat, {'reverse': reverse});
                }else{
                    $(obj).mask(maskFormat);
                }
            });
            $('.btn-suntikdana').on('click', function(e){
                e.preventDefault();
                $('#suntikdana').modal({
                    backdrop: 'static'
                });
            });
        });
    </script>
    @if (is_admin() || is_subadmin())        
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script>
            var link = "{{ route('admin.amounts.index') }}";
            var sound = new Audio("{{ asset('assets/sound/notif.mp3') }}");
            Pusher.logToConsole = false;
            var pusher = new Pusher('bc2af12caa5181e6f31b', {
                cluster: 'ap1'
            });
            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
                var respon = JSON.stringify(data);
                console.log(respon);
                sound.play();
                $.toast({
                    text : '<a class="alert-link border-0" href="'+link+'">Permintaan Suntik Dana baru dari '+data.name+'</a>',
                    position: 'top-right',
                    loaderBg: '#437512;',
                    icon: 'info',
                    hideAfter: 10000,
                })
            });
        </script>
    @endif
    @yield('script')
    
    <script src="{{asset('backend/js/app.min.js')}}"></script>
    @yield('script-bottom')
</body>
</html>