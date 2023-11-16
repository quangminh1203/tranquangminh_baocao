@if (count($list) > 0)
    <ul class="list-group mb-4">
        <li class="list-group-item active" aria-current="true">{{ $title }}</li>
        <li>
            <ul class="accordion-menus">
                @foreach ($list as $row_topic)
                    <x-sub-list-topic :topic="$row_topic" />
                @endforeach
            </ul>
        </li>
    </ul>
@endif
