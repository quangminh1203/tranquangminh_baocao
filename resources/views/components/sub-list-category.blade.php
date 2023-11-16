{{-- @if (count($list) > 0)

    <div class="card-header bg-orange text-center my-auto">
        <h3 class="fs-5 title m-0"><strong>{{ $title }}</strong></h3>
    </div>

    <ul id="menu mb-4">
        @foreach ($list as $item)
            <li class="ui-widget-header">
                <a href="{{ route('slug.home', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
            </li>

            @if (count($list2->parent_id == $item->id) > 0)
            @endif
        @endforeach
    </ul>



@endif --}}



@if (count($list2) > 0)
    <li class="link">
        <div class="dropdowns">
            <i class="fa-solid fa-layer-group"></i>
            <a href="{{ route('slug.home', ['slug' => $row_cate->slug]) }}">{{ $row_cate->name }}</a>
            <i class="fa fa-chevron-down" aria-hidden="true"></i>
        </div>
        <ul class="submenuItems">
            @foreach ($list2 as $item2)
                <li><a href="{{ route('slug.home', ['slug' => $item2->slug]) }}">{{ $item2->name }}</a></li>
            @endforeach
        </ul>
    </li>
@else
    <li class="link">
        <div class="dropdowns">
            <i class="fa-solid fa-layer-group"></i>
            <a href="{{ route('slug.home', ['slug' => $row_cate->slug]) }}">{{ $row_cate->name }}</a>           
        </div>
        
    </li>
@endif
