@extends('main.template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col m3 l3"> </div>
			<div class="col s12 m8 l8">
				<div class="content card center">
					<div class="card-content">
						<form role="form" method="POST" action="{{ url('/register') }}">
							<span class="card-title">Register</span>
							{!! csrf_field() !!}
							<div class="row">
								<div class="input-field col s12">
									<input id="username" type="text" name="username" class="validate{{ $errors->has('username') ? ' invalid' : '' }}"  value="{{ old('username') }}" pattern=".{6,}">
									@if ($errors->has('username'))
										<label for="username" data-error="{{ $errors->first('username') }}">Username</label>
									@else
										<label for="username" data-error="Please input 6 charactor or more" data-success="Validated">Username</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="password" type="password" name="password" class="validate{{ $errors->has('password') ? ' invalid' : '' }}" pattern=".{6,}">
									@if ($errors->has('password'))
										<label for="password" data-error="{{ $errors->first('password') }}">Password</label>
									@else
										<label for="password" data-error="Please input 6 charactor or more" data-success="Validated">Password</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="password_confirmation" type="password" name="password_confirmation" class="validate{{ $errors->has('password_confirmation') ? ' invalid' : '' }}" value="{{ old('password_confirmation') }}" pattern=".{6,}">
									@if ($errors->has('password_confirmation'))
										<label for="password_confirmation" data-error="{{ $errors->first('password_confirmation') }}">Confirm Password</label>
									@else
										<label for="password_confirmation" data-error="Please input 6 charactor or more" data-success="Validated">Confirm Password</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="email" type="email" name="email" class="validate{{ $errors->has('email') ? ' invalid' : '' }}" value="{{ old('email') }}">
									@if ($errors->has('email'))
										<label for="email" data-error="{{ $errors->first('email') }}">E-mail</label>
									@else
										<label for="email" data-error="Please fill E-mail address" data-success="Validated">E-mail</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="name" type="text" name="name" class="validate{{ $errors->has('name') ? ' invalid' : '' }}" value="{{ old('name') }}">
									@if ($errors->has('name'))
										<label for="name" data-error="{{ $errors->first('name') }}">Name</label>
									@else
										<label for="name" data-success="Validated">Name</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="card_id" type="text" name="card_id" class="validate{{ $errors->has('card_id') ? ' invalid' : '' }}" value="{{ old('card_id') }}" pattern="[0-9].{12}">
									@if ($errors->has('card_id'))
										<label for="card_id" data-error="{{ $errors->first('card_id') }}">รหัสบัตรประชาชน</label>
									@else
										<label for="card_id" data-error="Card ID had 13 digits and number only" data-success="Validated">รหัสบัตรประชาชน</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="col s12">
									<label for="gender">เพศ</label>
									<input class="with-gap" name="gender" type="radio" id="genderm" value="male" checked/>
									<label for="genderm">Male</label> &nbsp;
									<input class="with-gap" name="gender" type="radio" id="genderf" value="female"/>
									<label for="genderf">Female</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<textarea id="address" type="text" name="address" class="materialize-textarea validate{{ $errors->has('address') ? ' invalid' : '' }}" rows="4" cols="45">{{ old('address') }}</textarea> 
									@if ($errors->has('address'))
										<label for="address" data-error="{{ $errors->first('address') }}">ที่อยู่</label>
									@else
										<label for="address">ที่อยู่</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="tel" type="text" name="tel" class="validate{{ $errors->has('tel') ? ' invalid' : '' }}" value="{{ old('tel') }}" pattern="[0-9].{9}">
									@if ($errors->has('card_id'))
										<label for="tel" data-error="{{ $errors->first('tel') }}">หมายเลขโทรศัพท์</label>
									@else
										<label for="tel" data-error="Phone number had 10 digits and number only" data-success="Validated">หมายเลขโทรศัพท์</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="birthday" type="date" name="birthday" class="datepicker" value="{{ old('birthday') }}">
									@if ($errors->has('card_id'))
										<label for="birthday" data-error="{{ $errors->first('birthday') }}">วัน เดือน ปีเกิด</label>
									@else
										<label for="birthday">วัน เดือน ปีเกิด</label>
									@endif
								</div>
							</div>
							<input type="hidden" name="ip" value="{{Request::getClientIp()}}">
							<div class="row">
						  	<div class="center">
						  		<button type="submit" class="btn waves-effect waves-light">
									<i class="fa fa-btn fa-user-plus"></i> Register
								</button> 
							</div>
						  </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
