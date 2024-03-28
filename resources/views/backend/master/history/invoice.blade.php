
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            font-size: 14px;
            font-family: 'Arial Black';
            font-weight: bold; 
        }

        td, th, tr, table {
            border-top: 1px solid black;
            border-collapse: collapse;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        @media print {
            .hidden-print, .hidden-print * {
                display: none !important;
            }
        }
        @media print {
    .no-print {
        display: none;
       }
    }

    .btn-success {
        background-color: green; 
        color: white; 
        
    }

    </style>
</head>
<body>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="content">
                    <div class="card">
                        <div class="card-body">
                        <div class="container mb-5 mt-3">
                            <div class="no-print">
                                <div class="row d-flex align-items-baseline">
                                    <div class="col-xl-9">
                                        <p >Invoice  <strong> #{{ $test->oc_number }}</strong></p>
                                    </div>
                                </div>
                            </div>
                                <hr>
                            
                        <div id="printArea">
                                <div class="container">
                
                                    <div class="row">
                                        <div>
                                            <div style="text-align: left; color: black;"><span>{{ $test->oc_number }}</span></div>
                                            <div style="text-align: left; color: black;"><span>{{ $test->customer_name }}</span></div>
                                            <div style="text-align: left; color: black;">{{ $test->customer_address }}</div>
                                            <div style="text-align: left; color: black;">{{ $test->customer_phone }}</div>
                                            <div style="text-align: left; color: black;">{{ \Carbon\Carbon::parse($test->date_order)->format('d F Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="row my-2 mx-1 justify-content-center">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Product</th>
                                                    <th scope="col">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($details as $detail)
                                                <tr>
                                                    <td>{{ $detail->name }} <span>({{ $detail->qty}})</span></td>
                                                    <td>Rp {{ number_format($detail->sub_total, 0, ',', ',') }}</td>
                                                </tr>
                                            @endforeach
                                            
                                            </tbody>
                                        </table>
                                    </div>
                
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <p style="text-align: right;">
                                                <span>Total</span><br>
                                                <span>Rp{{ number_format($test->grand_total, 0, ',', ',') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 float-end">
                            <button class="btn btn-success text-capitalize border-0" data-mdb-ripple-color="green" style="width: 100%;" onclick="printDiv('printArea')">
                                <i class="fas fa-print text-primary"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>
</html>