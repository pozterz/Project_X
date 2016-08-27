@extends('material.template') 
@section('content')

<div class="page-content">
	<div class="mdl-grid max-width">
		<div class="mdl-cell mdl-cell--2-col mdl-cell--0-col-tablet"></div>
		<div class="mdl-cell mdl-cell--9-col mdl-cell--8-col-tablet">
			<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
				<thead>
					<tr>
						<th class="mdl-data-table__cell--non-numeric">#</th>
						<th class="mdl-data-table__cell--non-numeric">id</th>
						<th class="mdl-data-table__cell--non-numeric">Queue Name</th>
						<th class="mdl-data-table__cell--non-numeric">Counter</th>
						<th class="mdl-data-table__cell--non-numeric">Start - End</th>
						<th class="mdl-data-table__cell--non-numeric">Status</th>
					</tr>
				</thead>
				<tbody>
					@foreach($mainqueue as $key => $mq)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $mq->id }}</td>
						<td class="mdl-data-table__cell--non-numeric">{{ $mq->queue_name }}</td>
						<td class="mdl-data-table__cell--non-numeric">{{ $mq->counter }}</td>
						<td class="mdl-data-table__cell--non-numeric">{!! dateHelper::thformat($mq->start) !!} - {!! dateHelper::thformat($mq->end) !!}</td>
						@if($mq->status == 'ready')
							<td class="mdl-data-table__cell--non-numeric"><p class="text-primary">Ready</p></td>
						@elseif($mq->status == 'begin')
							<td class="mdl-data-table__cell--non-numeric"><p class="text-success">Begin</p></td>
						@endif
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="mdl-cell mdl-cell--3-col mdl-cell--0-col-tablet"></div>
	</div>
</div>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>


@endsection