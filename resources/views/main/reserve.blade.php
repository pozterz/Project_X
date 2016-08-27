@extends('main.template')

@section('content')

<div class="container">
	<div class="row">
		<div class="col l3"> </div>
		<div class="col s12 m12 l7">
			<div class="card">
				<div class="card-panel white z-depth-1">
					@if($mainqueue)
					 <ul class="collection with-header">
						<li class="collection-header">
							<h5 class="flow-text"><i class="fa fa-hashtag"></i> Activity name : {{$mainqueue->queue_name}} 
							<br>
						</li>
						<form action="" method="POST">
							{{ csrf_field() }}
							<li class="collection-item">
								Counter : {{ $mainqueue->counter }}
							</li>
							<li class="collection-item">
								Remaining : <span id="remaining"></span>
							</li>
							<li class="collection-item">
								Service time : {{ $mainqueue->opentime->format("d M | H:i") }}  | {{ $mainqueue->service_time }} Minutes/User
							</li>
							<li class="collection-item">
								Open : {{ $mainqueue->start->format("d M | H:i") }}
							</li>
							<li class="collection-item">
								<p id="{{$mainqueue->end}}">End : {{ $mainqueue->end->format("d M | H:i") }}</p>
							</li>
							<li class="collection-item">
								Status : 
								@if($mainqueue->status == 'ready')
									<u class="blue-text">Ready</u>
								@elseif($mainqueue->status == 'begin')
									<u class="green-text">Begin</u>
								@endif
							</li>
							<li class="collection-item">
								Count : {{ $mainqueue->current_count }}/{{ $mainqueue->max_count }}
							</li>
							<li class="collection-item">
								By :  {{ $owner->user_info->name }}
							</li>
							<li class="collection-item">
								Created : {{ $mainqueue->created_at->format("d M | H:i") }}
							</li>
							<li class="collection-item">
								{!! app('captcha')->display()!!}

							</li>
							<li class="collection-item center">
								<input type="hidden" name="id" value="{{ $mainqueue->id }}">
								<button type="submit" class="btn waves-effect waves-light blue">
									<i class="fa fa-btn fa-plus-circle"></i> Reserve
								</button> 
							</li>
						</form>
					  </ul>
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
		        $(this).html('หมดเวลา').parent().addClass('color red lighten-2');
		          $(this).parent().fadeOut('slow');
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