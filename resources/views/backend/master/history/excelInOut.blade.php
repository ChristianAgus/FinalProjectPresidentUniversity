
<table>
    <tr>
        <td style="vertical-align: sub;height:25px;font-size:14px;font-weight:bold;text-align: left;" colspan="10">Report Orders  
        </td>
    </tr>
    <tr></tr>
    <tr>
        <th style="width:30px;background: #e9e9e9;font-weight: bold;font-size:13px;">Oc number</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Customer Name</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Phone</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Address</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Pay Category</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Order Date</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Product</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Price</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Quantity</th>
        <th style="width:15px;background: #e9e9e9;font-weight: bold;font-size:13px;">Grand Total</th>

        
    </tr>
    @foreach($master as $masters)
    @php
    $Details = DB::table('order_details')->where('order_id', $masters->id)->get();
    @endphp
    @foreach ($Details as $details)
    <tr>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->oc_number}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->customer_name}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->customer_phone}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->customer_address}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->pay_category}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{Carbon\Carbon::parse($masters->created_at)->format("d/m/Y") }}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $details->name}}</td>
        <td style="word-wrap: break-word;text-align:left;">Rp {{ number_format($details->price, 0, ',', '.') }}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $details->qty}}</td>
        <td style="word-wrap: break-word;text-align:left;">Rp {{ number_format($details->sub_total, 0, ',', '.') }}</td>
        {{-- <td style="word-wrap: break-word;text-align:left;">{{ isset($masters->user_id) ? $masters->requestor->name : ""}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{Carbon\Carbon::parse($masters->created_at)->format("d/m/Y") }}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->po_no}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{Carbon\Carbon::parse($masters->need_on)->format("d/m/Y") }}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->paid_to}}</td>
        <td style="word-wrap: break-word;text-align:left;">                               
            @if ($masters->currency === 'IDR')
                @if (is_numeric($masters->amount))
                    {{ currencySymbol($masters->currency) }} {{ number_format($masters->amount) }} <span>( {{ $masters->currency }} ) </span>
                @else
                    {{ currencySymbol($masters->currency) }} {{ $masters->amount }} <span>( {{ $masters->currency }} ) </span>
                @endif
            @else
                {{ currencySymbol($masters->currency) }} {{ $masters->amount }} <span>( {{ $masters->currency }} ) </span>
            @endif
        </td>        
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->account_no}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->bank}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->bank_address}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->payment}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->description}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{ $masters->step_approval}}</td>
        <td style="word-wrap: break-word;text-align:left;">{{Carbon\Carbon::parse($masters->release_date)->format("d/m/Y") }}</td>
        <td style="word-wrap: break-word;text-align:left;">{{Carbon\Carbon::parse($masters->closed_date)->format("d/m/Y") }}</td> --}}
    </tr>
    @endforeach

    @endforeach
</table>
