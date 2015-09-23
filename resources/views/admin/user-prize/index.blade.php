@extends('admin.admin')

@section('style')
    <style>
        .am-selected.am-dropdown{
            width: 100%;
        }
        button.am-selected-btn.am-btn.am-dropdown-toggle.am-btn-sm.am-btn-default {
            width: 100%;
        }
    </style>
@stop

@section('content')


    <div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">{!! trans('admin.title') !!}</div>
            <div class="am-modal-bd">
                确定已兑奖吗？
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>确定</span>
            </div>
        </div>
    </div>


    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">获奖名单</strong> \ <small>{{$data->total() . '人'}}</small></div>
    </div>

    <div class="am-g mb">

        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
                    <a href="{{ Request::url() . '?excel=1' . current_url_paras(['page', 'excel'])}}" class="am-btn am-btn-success"><span class="am-icon-download"></span> 下载Excel</a>
                </div>
            </div>
        </div>

        <form>
            <div class="am-u-sm-12 am-u-md-3" style="padding: 0 5px;">
                <div class="am-form-group">
                        {{--<select name="privilege_id" id=""  class="am-form-field" data-am-selected="{btnSize: 'sm'}">--}}
                            {{--@foreach($privileges as $privilege)--}}
                                {{--<option value="{{$privilege['id']}}" {{Request::input('privilege_id') == $privilege['id'] ? 'selected' : ''}}>{{$privilege['name']}}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                </div>
            </div>
            <div class="am-u-sm-12 am-u-md-3" style="padding: 0 5px;">
                <div class="am-input-group am-input-group-sm">
                    <input type="text" name="keyword" class="am-form-field" value="{{Request::input('keyword') ? : old('keyword')}}" placeholder="输入手机号">
        <span class="am-input-group-btn">
          <button class="am-btn am-btn-default" type="submit">搜索</button>
        </span>
                </div>
            </div>
        </form>

    </div>




    <div class="am-g">
        <div class="am-u-sm-12">


            @if(count($data))

                <table class="am-table am-table-bd am-table-striped admin-content-table">
                    <thead>
                    <tr>
                        <th>昵称</th>
                        <th>电话</th>
                        <th>奖品</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($data as $user_prize)
                        <tr>
                            <td>
                                <img class="am-circle" src="{{$user_prize['user']['headimgurl']}}" width="30" height="30"/>
                                {{ $user_prize['user']['nickname'] }}
                            </td>
                            <td>{{ $user_prize['info'] ? $user_prize['info']['phone'] : '未填写'}}</td>
                            <td>{{ $user_prize['prize'] ? $user_prize['prize']['name'] : '无'}} </td>
                            <td>
                                @if( ! $user_prize['exchange'])
                                <div class="am-btn-toolbar">
                                    <div class="am-btn-group am-btn-group-xs">
                                        <form action="{!! route('admin.user-prize.store') !!}" method="POST">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="user_prize_id" value="{!! $user_prize['id'] !!}">
                                            <button type="submit" class="am-btn am-btn-default am-btn-xs"><span class="am-icon-pencil-square-o"></span> 兑奖</button>
                                        </form>
                                    </div>
                                </div>
                                @else
                                   已兑奖
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('partials.pagination', ['collection' => $data])


            @endif

        </div>
    </div>


@stop

@section('script')



@stop