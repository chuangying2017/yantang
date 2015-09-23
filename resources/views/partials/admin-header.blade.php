
<header class="am-topbar admin-header">
  <div class="am-topbar-brand">
    <a href="{{route('admin.dashboard')}}">
        <strong>{{Lang::get('admin.title')}}</strong> <small>{{Lang::get('admin.admin_title')}}</small>
    </a>
  </div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-users"></span> 管理员 <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
            <li><a href="{{route('admin.account.edit', [$auth_admin['id']])}}">修改密码</a></li>
            <li><a href="{{route('admin.logout')}}">登出</a></li>
        </ul>
      </li>
      <li class="am-hide-sm-only">
        <a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a>
      </li>
    </ul>
  </div>
</header>