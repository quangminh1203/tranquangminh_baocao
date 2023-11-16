@if (count($list2) > 0)
    <li class="link">
        <div class="dropdowns">
           <i class="fa-solid fa-globe"></i>
            <a href="{{ route('slug.home', ['slug' => $row_topic->slug]) }}">{{ $row_topic->name }}</a>
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
           <i class="fa-solid fa-globe"></i>
            <a href="{{ route('slug.home', ['slug' => $row_topic->slug]) }}">{{ $row_topic->name }}</a>           
        </div>
        
    </li>
@endif
