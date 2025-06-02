<form id="filter_form">
    <input type="hidden" name="tab" value="{{$tab}}">
    <div class="row">
        <div class="col-sm-12 col-md-6 align-self-end">
            <label class="d-flex align-items-center">
                Show &nbsp;
                <select name="limit" class="form-select form-select-sm entries-per-page" style="width: 70px" data-tab="{{$tab}}">
                    @if (Auth::user()->ledParts->count() > 0 || Auth::user()->ledTeams->count() > 0 || !Auth::user()->team)
                        @php
                            $pages = [15,25,50,100]
                        @endphp
                    @else
                        @php
                            $pages = [6,10,20]
                        @endphp
                    @endif
                    @foreach($pages as $perPage)
                        <option value="{{ $perPage }}" @if ($model->perPage() == $perPage) selected @endif>{{ $perPage }}</option>
                    @endforeach
                </select>
                &nbsp;
                entries
            </label>
        </div>
        <div class="d-flex col-sm-12 col-md-6 justify-content-end">
            <label>Search:
                <input type="text" name="filters[search_{{$type}}]" class="form-control form-control-sm" placeholder="" value="{{isset($filters['search_'.$type])?$filters['search_'.$type]:''}}">
            </label>
        </div>
    </div>
</form>