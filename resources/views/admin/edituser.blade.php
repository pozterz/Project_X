@extends('main.template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col s12 m2 l2"> </div>
			<div class="col s12 m8 l8">
				<div class="content card  z-depth-2">
					<div class="card-content">
						<form role="form" method="POST" action="{{ url('/PostEdit') }}/{{$user->id}}">
							<span class="card-title">Edit #{{$user->id}}</span>
							{!! csrf_field() !!}
							<div class="row">
								<div class="input-field col s12">
									<input id="username" type="text" name="username" class="validate{{ $errors->has('username') ? ' invalid' : '' }}"  value="{{ $user->username }}" pattern=".{6,}">
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
									<input id="email" type="email" name="email" class="validate{{ $errors->has('email') ? ' invalid' : '' }}" value="{{ $user->email }}">
									@if ($errors->has('email'))
										<label for="email" data-error="{{ $errors->first('email') }}">E-mail</label>
									@else
										<label for="email" data-error="Please fill E-mail address" data-success="Validated">E-mail</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="name" type="text" name="name" class="validate{{ $errors->has('name') ? ' invalid' : '' }}" value="{{ $user->user_info->name }}">
									@if ($errors->has('name'))
										<label for="name" data-error="{{ $errors->first('name') }}">Name</label>
									@else
										<label for="name" data-success="Validated">Name</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="card_id" type="text" name="card_id" class="validate{{ $errors->has('card_id') ? ' invalid' : '' }}" value="{{ $user->user_info->card_id }}" pattern="[0-9].{12}"  length="13">
									@if ($errors->has('card_id'))
										<label for="card_id" data-error="{{ $errors->first('card_id') }}">Card ID</label>
									@else
										<label for="card_id" data-error="Card ID had 13 digits and number only" data-success="Validated">Card ID</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="col s12">
									<p style="color:#9e9e9e;">
										Gender : 
										@if($user->user_info->gender == "male")
										<input class="with-gap" name="gender" type="radio" id="genderm" value="male" checked/>
										<label for="genderm">Male</label> &nbsp;
										<input class="with-gap" name="gender" type="radio" id="genderf" value="female"/>
										<label for="genderf">Female</label>
										@else
										<input class="with-gap" name="gender" type="radio" id="genderm" value="male" />
										<label for="genderm">Male</label> &nbsp;
										<input class="with-gap" name="gender" type="radio" id="genderf" value="female" checked/>
										<label for="genderf">Female</label>
										@endif
									</p>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<textarea id="address" type="text" name="address" class="materialize-textarea validate{{ $errors->has('address') ? ' invalid' : '' }}" rows="4" cols="45">{{ $user->user_info->address }}</textarea> 
									@if ($errors->has('address'))
										<label for="address" data-error="{{ $errors->first('address') }}">Address</label>
									@else
										<label for="address">Address</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="tel" type="text" name="tel" class="validate{{ $errors->has('tel') ? ' invalid' : '' }}" value="{{ $user->user_info->tel }}" pattern="[0-9].{9}" length="10">
									@if ($errors->has('tel'))
										<label for="tel" data-error="{{ $errors->first('tel') }}">Phone</label>
									@else
										<label for="tel" data-error="Phone number had 10 digits and number only" data-success="Validated">Phone</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="birthday" type="date" name="birthday" class="datepicker" value="{{ $user->user_info->birthday }}">
									@if ($errors->has('birthday'))
										<label for="birthday" data-error="{{ $errors->first('birthday') }}">Birthday</label>
									@else
										<label for="birthday">Birthday</label>
									@endif
								</div>
							</div>
							<input type="hidden" name="ip" value="{{Request::getClientIp()}}">
							<div class="row">
						  	<div class="center">
						  		<button type="submit" class="btn waves-effect waves-light blue">
									<i class="fa fa-btn fa-save"></i> Save
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