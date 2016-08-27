@extends('main.template')

<!-- Main Content -->
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Reset Password</div>
				<div class="panel-body">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
						<div class="col-xs-0 col-sm-3 col-md-3 col-lg-3"></div>
						<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
						{!! csrf_field() !!}

						<div class="form-group">

							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label{{ $errors->has('email') ? ' is-invalid' : '' }}">
								<input type="email" class="mdl-textfield__input" name="email" value="{{ old('email') }}">
								<label class="mdl-textfield__label" for="email">E-Mail Address</label>

								@if ($errors->has('email'))
									<span class="mdl-textfield__error">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-9">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-btn fa-envelope"></i>Send Password Reset Link
								</button>
							</div>
						</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
