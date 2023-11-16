@foreach ($list_menu as $row_menu)
    <li class="nav-item mx-auto">
        <a class="nav-link " aria-current="page" href="{{ route('slug.home', ['slug' => $row_menu->link]) }}">{{ $row_menu->name }}
        </a>
    </li>
@endforeach
