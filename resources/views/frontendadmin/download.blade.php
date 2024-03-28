<!DOCTYPE html>
<html>
<style type="text/css">
    body {
        margin: 0;
        font-family: Muli,-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
        font-weight: 400;
        line-height: 1.5;
        font-size: 10px;
    }
	.text-center {
		text-align: center;
	}
    .text-right {
		text-align: right;
	}

    .table-bordered, .table-bordered td, .table-bordered th {
        border: 1px solid #b0b0b0
    }
    .table-bordered2 {
        border: 1px solid #b0b0b0
    }
    .ml-2 {
        margin-left:2px;
    }
    .bg-th {
        background-color: #dfdfe1;
    }
    .pb-3 {
        padding-bottom: 3px;
    }
</style>
    <body>
        <table> 
	        <tr>
	        	<td>
                    <img src="{{ public_path('/uploads/frontend/haldinfoods.png') }}" alt='Logo Haldin' style="width: 60px; height: 60px" />
                </td>
	        </tr>
        </table>
        <br/>
        <br/>
        <table class="table-bordered2 text-center" width="30%">
            <tr>
                <td>Order Confirmation<br/>
                {{ $order->oc_number }}
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <table class="pb-3">
            <tbody>
                <tr>
                    <th width="60px">Date</th>
                    <th width="15px"> : </th>
                    <th>{{ Carbon\Carbon::parse($order->order_date)->formatLocalized("%B, %d %Y") }}</th>
                </tr>
                <tr>
                    <th>Ship To</th>
                    <th> : </th>
                    <th>{{ $order->customer_name }}</th>
                </tr>
                <tr>
                    <th>Phone</th>
                    <th> : </th>
                    <th>{{ $order->customer_phone }}</th>
                </tr>
                <tr>
                    <th>Address</th>
                    <th> : </th>
                    <th>{{ $order->customer_address }}</th>
                </tr>
            </tbody>
        </table>
        <br/>
        <br/>

        <table class="table-bordered" width="100%">
            <tr>
                <th class="text-center bg-th">No.</th>

                <th class="text-center bg-th">Product Code</th>
                <th class="text-center bg-th">Product Name</th>

                <th class="text-center bg-th">Price (IDR)</th>
                <th class="text-center bg-th">Qty</th>
                <th class="text-center bg-th">Total</th>
            </tr>
            @foreach($order_detail as $order_details)
            <tr>
                <td class="text-center">{{ $nomor++ }}</td>
                <td class="ml-2">{{ $order_details->products->sku }}</td>
                <td>{{ $order_details->name }}</td>
                <td class="ml-2 text-right">{{ number_format($order_details->price/1.11) }}</td>
                <td class="ml-2 text-center">{{ $order_details->qty }}</td>
                <td class="text-right">{{ number_format($order_details->sub_total/1.11) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-right">Sub Total (IDR)</td>
                <td class="text-right">{{ number_format($order->grand_total/1.11) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right">PPN 11%</td>
                <td class="text-right">{{ number_format($order->grand_total/1.11*0.11) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right">Grand Total (IDR)</td>
                <td class="text-right">{{ number_format($order->grand_total,0) }}</td>
            </tr>
        </table>
        <p>&nbsp;</p>
        <table width="100%">
            <tr>
                <th class="text-center">Haldin appreciate your business,</th>
            
                <th class="text-center">Customer Approved by,</th>
            </tr>
            <tr>
                <th class="text-center">&nbsp;</th>
                <th class="text-center">&nbsp;</th>
            </tr>
            <tr>
                <th class="text-center"><i>{{ $order->sales_name }}</i></th>
                <th class="text-center">&nbsp;</th>
            </tr>
            <tr>
                <th class="text-center">&nbsp;</th>
                <th class="text-center">&nbsp;</th>
            </tr>
            <tr>
                <td class="text-center" style="text-decoration: underline;">{{ $order->sales_name }}</td>
                <td class="text-center" style="text-decoration: underline;">{{ $order->customer_name }}</td>
            </tr>
        </table>

        
    </body>
</html>

