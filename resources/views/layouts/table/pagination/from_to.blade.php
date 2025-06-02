<div class="row mt-3">
    <div class="col-xl-3 col-md-6 mb-2">
        <label for="from-date-packages" class="form-label">Select Date Range : </label>
        <div class="input-group">
            <span class="input-group-text bg-transparent"><i class='bx bx-calendar-event'></i></span>
            <input type="text" class="result form-control from-to-range" form="filter_form" placeholder="{{ $filters ? ($filters['fromTo_' . $type] ?? 'Select Date') : 'Select Date' }}">
            <input type="hidden" class="rangeDate" form="filter_form" value="{{ $filters ? ($filters['fromTo_' . $type] ?? '') : '' }}" name="filters[fromTo_{{$type}}]">
        </div>
    </div>
    <div class="col align-self-end pb-2">
        <button type="submit" class="btn btn-primary" form="filter_form">Search</button>
    </div>
</div>