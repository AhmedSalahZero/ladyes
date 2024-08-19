<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap');
        *{
            margin: 0;
            padding: 0;
        }
        body{
            background-color:gainsboro;
        }
        .printEl {
            width: 8.5in;
            height: 11in;
            background-color:white;
            margin: 0 auto;
            font-family: 'Open Sans', sans-serif;
            padding: 40px 30px;
        }
        table, th, td {
            border: 1px solid rgb(190, 190, 190);
            border-collapse: collapse;
        }
        th, td {
            padding: 4px 12px;
            text-align: left;
            color: #181818;
        }
        .logo_qr_table{
            border: none;
            margin-bottom: 40px;
        }
        .logo_qr_table td , .logo_qr_table th{
            border: none;
        }
        .logo_qr_table img{
            object-fit: contain;
            height: 100px;
            width: 200px;
        }
    </style>
</head>
<body>
<div class="printEl">

    <table style="width:100%" class="logo_qr_table">

        <tr>
            <th style="width:50%;text-align: left;">
                <img width="200" src="{{asset('/public'.$setting->logo)}}" alt="">
            </th>
{{--            <td style="width:50%;text-align: right;">--}}
{{--                <img width="200" src="https://api.qrserver.com/v1/create-qr-code/?data='.{{$qr_data}}.'&amp;size=80x80" alt="">--}}
{{--            </td>--}}
        </tr>
    </table>

    <table style="width:100%;">
        <tr>
            <th>{{__('msg.invoice')}}</th>
            <td># {{$order->code ?? ''}}</td>
        </tr>
        <tr>
            <th>{{__('msg.customer')}}</th>
            <td>{{isset($order->user) ? $order->user->name : $order->name}}</td>
        </tr>
        <tr>
            <th>{{__('msg.email')}}</th>
            <td>{{isset($order->user) ? $order->user->email : $order->email}}</td>
        </tr>
        <tr>
            <th>{{__('msg.phone')}}</th>
            <td>{{isset($order->user) ? $order->user->phone : $order->phone}}</td>
        </tr>
        <tr>
            <th>{{__('msg.status')}}</th>
            <td>{{$order->status_name}}</td>
        </tr>
        <tr>
            <th>{{__('msg.is_payment')}}</th>
            <td>{{$order->is_payment == 1 ? 'مدفوع' : 'غير مدفوع'}}</td>
        </tr>
        <tr>
            <th>{{__('msg.total')}}</th>
            <td>{{(float)$order->total}} {{__('msg.sar')}}</td>
        </tr>
    </table>


    <table style="width:100%;margin-top: 50px;">
        <tr>
            <th>#</th>
            <th>{{__('msg.product')}}</th>
            <th>{{__('msg.category')}}</th>
            <th>{{__('msg.price')}}</th>
            <th>{{__('msg.qty')}}</th>
            <th>{{__('msg.total')}}</th>
        </tr>
        @if(isset($order->details) && count($order->details) > 0)
            @foreach($order->details as $k => $detail)
                <tr class="product_{{$detail->id}}">
                    <td>{{$k+1}}</td>
                    <td>{{$detail->product_name}}</td>
                    <td>{{isset($detail->product->category) ? $detail->product->category->name : ''}}</td>
                    <td>{{(float)$detail->price}}</td>
                    <td>{{$detail->qty}}</td>
                    <td>{{(float)$detail->sub_total}}</td>
                </tr>
            @endforeach
        @endif
    </table>

    <table style="width:50%;margin-top: 4px;">
        <tr>
            <th>{{__('msg.sub_total')}}</th>
            <td>{{(float)$order->order_sub_total}} {{__('msg.sar')}}</td>
        </tr>
        <tr>
            <th>{{__('msg.vat')}} ({{$order->vat}} %)</th>
            <td>{{(float)$order->total_tax}} {{__('msg.sar')}}</td>
        </tr>
        <tr>
            <th>{{__('msg.total')}}</th>
            <td>{{(float)$order->total}} {{__('msg.sar')}}</td>
        </tr>
    </table>
</div>
</body>
</html>
