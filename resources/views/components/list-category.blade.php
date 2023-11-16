@if (count($list) > 0)
    <ul class="list-group mb-4">
        <li class="list-group-item active" aria-current="true">{{ $title }}</li>
        <li>
            <ul class="accordion-menus">
                @foreach ($list as $row_cate)
                    <x-sub-list-category :category="$row_cate" />
                @endforeach
            </ul>
        </li>
    </ul>
@endif
