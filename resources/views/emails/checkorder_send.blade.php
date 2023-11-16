
<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        th {
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Xác nhận đơn hàng</h2>
    <h2>Xin chào {{ $user->name }}</h2>
        <P>Để xác nhận rằng chính bạn đã đặt hàng vui lòng nhấn vào nút xác nhận bên dưới.</P>
        <p style="color: red;">Lưu ý:Liên kết này chỉ có hiệu lực trong vòng 24h nếu không xác nhậ đơn hàng sẽ tự động hủy</p>

        
    <h3>Thông tin người đặt hàng</h3>
    <table>
        <tr>
            <th>Tên</th>
            <th>Số điện thoại</th>
            <th>Email</th>
        </tr>
        <tr>
            <td> {{ $user->name }}</td>
            <td> {{ $user->phone }}</td>
            <td> {{ $user->email }}</td>
        </tr>
    </table>

    <h3>Thông tin người nhận</h3>
    <table>
        <tr>
            <th>Tên người nhận</th>
            <th>Số điện thoại người nhận</th>
            <th>Email người nhận</th>
            <th>Địa chỉ giao hàng</th>
        </tr>
        <tr>
            <td>{{ $order->deliveryname }}</td>
            <td>{{ $order->deliveryphone }}</td>
            <td>{{ $order->deliveryemail }}</td>
            <td>{{ $order->deliveryaddress }}</td>
        </tr>
    </table>
    <h3>Chi tiết đơn hàng</h3>
    <table>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Thành tiền</th>
        </tr>
        @php
           $orderdetail= $order->orderdetail;
           $total_price=0;
        @endphp
        @foreach ($orderdetail as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ number_format($item->price) }} VND</td>
            <td>{{ number_format($item->qty *  $item->price)   }} VND</td>
            @php
                $total_price += $item->qty * $item->price;
            @endphp
        </tr>
        @endforeach
        
        <tr>
            <td colspan="2">Tổng cộng</td>
            <td>{{ number_format($total_price +35000) }}  VND gồm( 35000 VND phí vận chuyển )</td>
        </tr>
        <tr>
            <td colspan="4">Trạng thái <span style="color: #da1608">chưa xác nhận</span></td>
          
        </tr>
    </table>
    {{-- <div style="text-align: center">
        <a  href="{{ route('site.orderaccept',['id'=>$order->id,'accept_token'=>$order->accept_token]) }}" style="font-size: medium; display: inline-block; background-color: green; color: white; padding: 7px 25px; font-weight: bold;">Xác nhận ngay</a>
    </div> --}}
  
</body>
</html>