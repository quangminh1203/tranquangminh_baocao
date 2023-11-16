@foreach ($list_menu as $row_menu)
    <x-sub-main-menu :rowmenu="$row_menu" />
@endforeach
