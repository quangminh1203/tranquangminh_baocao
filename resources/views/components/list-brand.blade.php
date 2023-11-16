
@if (count($list) > 0)
    <ul class="list-group mb-4">
        <li class="list-group-item active" aria-current="true">{{ $title }}</li>
        <li>
            <ul class="accordion-menus">
                @foreach ($list as $item)
                    <li class="link">
                        <div class="dropdowns">
                            <i class="fa-solid fa-user-astronaut"></i>
                           <a href="{{ route('slug.home', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                        </div>

                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
@endif
