@extends('main.template')

@section('content')

<div class="container">
	<div class="row">
		<div class="col s12 m12 l8 offset-l2">
			<div class="card">
				<div class="card-panel white z-depth-1">
					@if($mainqueue)
					 <ul class="collection with-header">
						<li class="collection-header red-border">
							<h5 class="flow-text"><i class="fa fa-hashtag"></i> Activity name : {{$mainqueue->queue_name}} 
							<br>
						</li>
						<form action="" method="POST">
							{{ csrf_field() }}
							<li class="collection-item blue-border">
								Counter : {{ $mainqueue->counter }}
							</li>
							<li class="collection-item blue-border">
								Remaining : <span id="remaining"></span>
							</li>
							<li class="collection-item blue-border">
								Service time : {{ $mainqueue->opentime->format("j F Y | H:i") }}  | {{ $mainqueue->service_time }} Minutes/User
							</li>
							<li class="collection-item blue-border">
								Open : {{ $mainqueue->start->format("j F Y | H:i") }}
							</li>
							<li class="collection-item blue-border">
								<p id="{{$mainqueue->end}}">End : {{ $mainqueue->end->format("j F Y | H:i") }}</p>
							</li>
							<li class="collection-item blue-border">
								Status : 
								@if($mainqueue->start > Carbon\Carbon::now())
									<span class="blue-text">Ready</span>
								@elseif($mainqueue->end >= Carbon\Carbon::now() && $mainqueue->start <= Carbon\Carbon::now())
									@if($mainqueue->current_count == $mainqueue->max_count)
										<span class="red-text">Full</span>
									@else
										<span class="green-text">Begin</span>
									@endif
								@elseif($mainqueue->end < Carbon\Carbon::now() && $mainqueue->opentime > Carbon\Carbon::now())
									<span class="red-text">Closed</span>
								@elseif($mainqueue->end < Carbon\Carbon::now() && $mainqueue->opentime < Carbon\Carbon::now())
									<span class="blue-text">Passed</span>
								@endif
							</li>
							<li class="collection-item blue-border">
								Count : {{ $mainqueue->current_count }}/{{ $mainqueue->max_count }}
							</li>
							<li class="collection-item blue-border">
								By :  {{ $mainqueue->user->user_info->name }}
							</li>
							<li class="collection-item blue-border">
								Created : {{ $mainqueue->created_at->format("j F Y | H:i") }}
							</li>
						</form>
					  </ul>
					  <div class="center">
						  <a onclick="history.go(-1);return true;" title="Back"><button type="button" class="btn waves-effect waves-light red"><i class="fa fa-arrow-left"></i>  Back</button></a>
								<a href="{{ url('admin/editActivity') }}/{{ $mainqueue->id }}"><button type="button" class="btn waves-effect waves-light blue"><i class="fa fa-edit"></i> Edit</button></a>
						</div>
					@else
						<span class="card-title">Activity not found.</span>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js')
	<script>
		$(document).ready(function(){
			var end = $('.collection-item p').attr('id');

			$('#remaining').countdown(end)
		      .on('update.countdown', function(event) {
		        var format = '%H:%M:%S';
		        if(event.offset.totalDays > 0) {
		          format = '%-d day%!d ' + format;
		        }
		        if(event.offset.weeks > 0) {
		          format = '%-w week%!w ' + format;
		        }
		        $(this).html(event.strftime(format));
		      })
		      .on('finish.countdown', function(event) {
		        $(this).html('หมดเวลา').parent().addClass('color orange lighten-4');
		      });
		})
		@if($errors->has('g-recaptcha-response'))
			Materialize.toast('{{ $errors->first('g-recaptcha-response') }}',3000,'rounded');
		@endif
		@if(Session::has('success'))
			Materialize.toast('{{ Session::get('success') }}',3000,'rounded');
		@endif
	</script>
@endsection