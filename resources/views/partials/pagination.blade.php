<ul class="am-pagination am-fr">
    <?php
    $lastPage = $collection->lastPage();
    $showCount = 5;
    $currentPage = $collection->currentPage();
    ?>

    <li class="{{$currentPage == 1 ? 'am-active' : '' }}">
        <?php $query = '' ; ?>
        @foreach(Request::all() as $key => $para)
            @if($key != 'page')
                <?php $query .= '&'.$key . '=' . $para ?>
            @endif
        @endforeach
        <a href="{{ Request::url() . '?page=' . ($currentPage - 1) . $query}}"  class="{{$currentPage <= 1 ? 'none' : '' }}" >上一页</a>
    </li>



    @if($lastPage <= 10)
        @for($i = 1; $i <= $lastPage; $i++ )
            <?php echo getPaginationQueryUrl($i, $collection); ?>
        @endfor
    @else

        @if($currentPage < $showCount)
            @for($i = 1; $i <= $showCount; $i++ )
                <?php echo getPaginationQueryUrl($i, $collection); ?>
            @endfor
            ...
            <?php echo getPaginationQueryUrl($lastPage, $collection); ?>
        @elseif($currentPage > $lastPage - $showCount)
            <?php echo getPaginationQueryUrl(1, $collection); ?>
            ...
            @for($i = $lastPage - $showCount; $i <= $lastPage; $i++ )
                <?php echo getPaginationQueryUrl($i, $collection); ?>
            @endfor
        @else
            <?php echo getPaginationQueryUrl(1, $collection); ?>
            ...

            @for($i = $currentPage - 2; $i <= $currentPage - 3 + $showCount; $i++ )
                <?php echo getPaginationQueryUrl($i, $collection); ?>
            @endfor

            ...
            <?php echo getPaginationQueryUrl($lastPage, $collection); ?>
        @endif

    @endif

    <li class="{{$currentPage == 1 ? 'am-active' : '' }}">
        <?php $query = current_url_paras(['page']) ; ?>

        <a href="{{ Request::url() . '?page=' . ( $currentPage + 1 ). $query}}" class="{{$currentPage + 1 >= $lastPage ? 'none' : '' }}"  >下一页</a>
    </li>


    @if(count($collection))
        <form action="" method="GET" role="form" class="am-form am-form-inline am-fr">

            <div class="am-form-group">
                <input type="text" id="" name="page" placeholder="页数" style="width: 100px;">
            </div>

            <button type="submit" class="am-btn am-btn-primary">跳转</button>
        </form>
    @endif


</ul>


<?php

function getPaginationQueryUrl($i, $collection)
{
    $query = current_url_paras(['page']) ;

    return '<li class="' . ($collection->currentPage() == $i ? 'am-active' : '') . '">
                    <a href="' . Request::url() . '?page=' . $i . $query . '">'. $i . '</a>
                </li>';
}



?>