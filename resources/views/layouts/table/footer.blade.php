<div class="row mt-3">
    <div class="col-sm-12 col-md-5 align-items-center d-flex justify-content-start">
        <div class="dataTables_info" id="all-packages_info" role="status" aria-live="polite">
            Showing {{ $model->firstItem() }} to {{ $model->lastItem() }} of {{ $model->total() }} entries
        </div>
    </div>
    <div class="col-sm-12 col-md-7 align-items-center d-flex justify-content-end">
        <div class="dataTables_paginate paging_simple_numbers" id="history-table_paginate">
            <ul class="pagination">
                <!-- Previous Page Link -->
                @if ($model->onFirstPage())
                    <li class="paginate_button page-item previous disabled" id="history-table_previous">
                        <a href="#" class="page-link">Prev</a>
                    </li>
                @else
                    <li class="paginate_button page-item previous">
                        <a href="{{ $model->previousPageUrl().'&limit='.$model->perPage() }}&tab={{$tab}}@foreach ($filters as $index => $filter)@if(is_array($filter))@foreach($filter as $key => $value)&{{ $check_type }}_filters[{{ urlencode($index) }}][]={{ urlencode($value) }}@endforeach @else&{{ $check_type }}_filters[{{ urlencode($index) }}]={{ urlencode($filter) }}@endif @endforeach&sort={{request('sort')}}&order={{request('order')}}" class="page-link">Prev
                        </a>
                    </li>
                @endif
    
                <!-- First Page Link -->
                <li class="paginate_button page-item @if ($model->currentPage() === 1) active @endif">
                    <a href="{{ $model->url(1).'&limit='.$model->perPage() }}&tab={{$tab}}@foreach ($filters as $index => $filter)@if(is_array($filter))@foreach($filter as $key => $value)&{{ $check_type }}_filters[{{ urlencode($index) }}][]={{ urlencode($value) }}@endforeach @else&{{ $check_type }}_filters[{{ urlencode($index) }}]={{ urlencode($filter) }}@endif @endforeach&sort={{request('sort')}}&order={{request('order')}}" class="page-link">1
                    </a>
                </li>
    
                @if ($model->currentPage() > 3)
                    <li class="paginate_button page-item disabled" id="history-table_ellipsis">
                        <a href="#" class="page-link">…</a>
                    </li>
                @endif
    
                @for ($i = max(2, $model->currentPage() - 2); $i <= min($model->lastPage() - 1, $model->currentPage() + 2); $i++)
                    <li class="paginate_button page-item @if ($i == $model->currentPage()) active @endif">
                        <a href="{{ $model->url($i).'&limit='.$model->perPage() }}&tab={{$tab}}@foreach ($filters as $index => $filter)@if(is_array($filter))@foreach($filter as $key => $value)&{{ $check_type }}_filters[{{ urlencode($index) }}][]={{ urlencode($value) }}@endforeach @else&{{ $check_type }}_filters[{{ urlencode($index) }}]={{ urlencode($filter) }}@endif @endforeach&sort={{request('sort')}}&order={{request('order')}}" class="page-link">{{ $i }}
                        </a>
                    </li>
                @endfor
    
                @if ($model->currentPage() < $model->lastPage() - 2)
                    <li class="paginate_button page-item disabled" id="history-table_ellipsis">
                        <a href="#" class="page-link">…</a>
                    </li>
                @endif
    
                <!-- Last Page Link -->
                @if ($model->lastPage() != 1)
                    <li class="paginate_button page-item @if ($model->currentPage() === $model->lastPage()) active @endif">
                        <a href="{{ $model->url($model->lastPage()).'&limit='.$model->perPage() }}&tab={{$tab}}@foreach ($filters as $index => $filter)@if(is_array($filter))@foreach($filter as $key => $value)&{{ $check_type }}_filters[{{ urlencode($index) }}][]={{ urlencode($value) }}@endforeach @else&{{ $check_type }}_filters[{{ urlencode($index) }}]={{ urlencode($filter) }}@endif @endforeach&sort={{request('sort')}}&order={{request('order')}}" class="page-link">{{ $model->lastPage() }}
                        </a>
                    </li>
                @endif
    
                <!-- Next Page Link -->
                @if ($model->hasMorePages())
                    <li class="paginate_button page-item next" id="history-table_next">
                        <a href="{{ $model->nextPageUrl().'&limit='.$model->perPage() }}&tab={{$tab}}@foreach ($filters as $index => $filter)@if(is_array($filter))@foreach($filter as $key => $value)&{{ $check_type }}_filters[{{ urlencode($index) }}][]={{ urlencode($value) }}@endforeach @else&{{ $check_type }}_filters[{{ urlencode($index) }}]={{ urlencode($filter) }}@endif @endforeach&sort={{request('sort')}}&order={{request('order')}}" class="page-link">
                            Next
                        </a>
                    </li>
                @else
                    <li class="paginate_button page-item next disabled">
                        <a href="#" class="page-link">Next</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>