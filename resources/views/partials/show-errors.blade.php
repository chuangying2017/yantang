@if(Session::has('message'))
    <div class="am-alert am-alert-{{session('type', 'default')}}" data-am-alert>
        <button type="button" class="am-close">&times;</button>
        <p>{{session('message')}}</p>
    </div>
@endif

@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="am-alert am-alert-{{Session::get('type', 'danger')}}"  data-am-alert>
            <button type="button" class="am-close">&times;</button>
            <p>{{ $error }}</p>
    </div>
    @endforeach
@endif
