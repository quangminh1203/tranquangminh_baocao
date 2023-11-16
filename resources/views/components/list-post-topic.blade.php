<ul class="list-group mb-4">
    <li class="list-group-item active" aria-current="true">{{ $title }}</li>
    <li>
        <ul class="accordion-menus">
            @foreach ($list as $post_item)
                <li class="link">
                    <div class="dropdowns px-0 border-bottom">

                        <a class="text-muted" href="{{ route('slug.home', ['slug' => $post_item->slug]) }}">{{ $post_item->title }}</a>

                    </div>

                </li>
            @endforeach


        </ul>
    </li>
</ul>
