@extends('material.template') 
@section('content')

<div class="page-content">
	<div class="mdl-grid max-width">
		<div class="mdl-cell mdl-cell--3-col"></div>
		<div class="mdl-grid mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet mdl-card mdl-shadow--4dp">
			<div class="mdl-cell mdl-cell--8-col">
				<div class="mdl-card__title">
			 		<h2 class="mdl-card__title-text">Login</h2>
				</div>
				<div class="mdl-card__supporting-text">
				<!-- Textfield with Floating Label -->
					<form action="#">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
							<input class="mdl-textfield__input" name="username" type="text" id="username">
							<label class="mdl-textfield__label" for="username">Username</label>
						</div>
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
							<input class="mdl-textfield__input" name="password" type="password" id="password">
							<label class="mdl-textfield__label" for="password">Password</label>
						</div>
						<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
							<i class="material-icons">sort</i> Login
						</button>
					</form>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--3-col"></div>
	</div>
</div>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>


@endsection