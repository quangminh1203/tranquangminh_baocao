@if ($checksub)
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle  mt-0" href="{{ route('slug.home', ['slug' => $row_menu->link]) }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ $row_menu->name }}
        </a>
        <!-- <a href="https://ln.hako.vn/" class="position-absolute" style='inset:0'></a> -->
        <ul class="dropdown-menu fs-5">
            @foreach ($list_menu1 as $row_menu1)
                <li><a class="dropdown-item py-3 fs-4 border-bottom"
                        href="{{ route('slug.home', ['slug' => $row_menu1->link]) }}"> {{ $row_menu1->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </li>
@else
    <li class="nav-item">
        <a class="nav-link  mt-0" href="{{ route('slug.home', ['slug' => $row_menu->link]) }}">
            {{ $row_menu->name }}

        </a>
    </li>
@endif
