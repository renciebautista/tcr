<div class="w-box">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                {{ $audit->description }}
            </h2>
            <p>Date Range: {{ $audit->start_date }} - {{ $audit->end_date }}</p>
        </div>
        <div class="col-xs-12">
            {!! link_to_route('audits.index','Back',array(),['class' => 'btn btn-default']) !!}
        </div>

      </div>
</div>