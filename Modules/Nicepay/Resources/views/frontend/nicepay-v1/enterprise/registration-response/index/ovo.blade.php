{{-- App\Http\ViewComposers\Frontend\NicepayV1\Enterprise\RegistrationResponse\Index\VirtualAccountComposer --}}

@extends('frontend/layouts/header')

@section('contents')
    <div class="complete-order margin-top-145 margin-top-162-mobile">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- bulet2 --> <br />
					
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						   
							<div class="modal-body">
									<div class="introduction-text text-center">
										@if($code =='0000')
										<div class="alert alert-success">
											<strong>Success!</strong> Pembayaran Berhasil
										</div>
										@endif
																				
										@if($code =='17')
										<div class="alert alert-danger">
											<strong>Warning!</strong> Pembayaran Dibatalkan
										</div>
										@endif
										
										@if($code =='1004')
										<div class="alert alert-danger">
											<strong>Warning!</strong> Waktu Pembayaran Habis
										</div>
										@endif
										
										@if($code =='9823')
										<div class="alert alert-danger">
											<strong>Warning.</strong> Link Sudah Kadaluarsa
										</div>
										@endif
										
										@if($code =='14')
										<div class="alert alert-danger">
											<strong>Warning.</strong> {{ $pesan }}
										</div>
										@endif
									</div>
									<div id="divOuter" class="padding-top-40 padding-bottom-40">
										<div id="divInner">											
										   <img src="{{asset('frontend/images/payment/ovo.jpg')}}" class="img-responsive" alt="">
										</div>
									</div>
									<div class="flex-box padding-bottom-20">
										<button type="button" class="btn txt-white txt-upper background-green btn-activate">
										<a href="{{ route('home.index') }}">
										Home Page
										</a>
										</button>
									</div>
									<div class="flex-box">
										
									</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

@endsection
