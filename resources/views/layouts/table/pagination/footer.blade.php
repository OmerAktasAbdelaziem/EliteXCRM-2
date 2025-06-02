<div class="row w-100">
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
                        <a href="#" class="page-link" aria-controls="history-table">prev</a>
                    </li>
                @else
                    <li class="paginate_button page-item previous">
                        <a href="{{ request()->fullUrlWithQuery(['tab'=>$tab??'0','page' => $model->currentPage() - 1]) }}" class="page-link" aria-controls="history-table">prev</a>
                    </li>
                @endif
    
                <!-- First Page Link -->
                <li class="paginate_button page-item @if ($model->currentPage() === 1) active @endif">
                    <a href="{{ request()->fullUrlWithQuery(['tab'=>$tab??'0','page' => 1]) }}" class="page-link" aria-controls="history-table">1</a>
                </li>
    
                @if ($model->currentPage() > 3)
                    <li class="paginate_button page-item disabled" id="history-table_ellipsis">
                        <a href="#" class="page-link" aria-controls="history-table">…</a>
                    </li>
                @endif
    
                @for ($i = max(2, $model->currentPage() - 2); $i <= min($model->lastPage() - 1, $model->currentPage() + 2); $i++)
                    <li class="paginate_button page-item @if ($i == $model->currentPage()) active @endif">
                        <a href="{{ request()->fullUrlWithQuery(['tab'=>$tab??'0','page' => $i]) }}" class="page-link" aria-controls="history-table">{{ $i }}</a>
                    </li>
                @endfor
    
                @if ($model->currentPage() < $model->lastPage() - 2)
                    <li class="paginate_button page-item disabled" id="history-table_ellipsis">
                        <a href="#" class="page-link" aria-controls="history-table">…</a>
                    </li>
                @endif
    
                <!-- Last Page Link -->
                @if ($model->lastPage() != 1)
                    <li class="paginate_button page-item @if ($model->currentPage() === $model->lastPage()) active @endif">
                        <a href="{{ request()->fullUrlWithQuery(['tab'=>$tab??'0','page' => $model->lastPage()]) }}" class="page-link" aria-controls="history-table">{{ $model->lastPage() }}</a>
                    </li>
                @endif
    
                <!-- Next Page Link -->
                @if ($model->hasMorePages())
                    <li class="paginate_button page-item next" id="history-table_next">
                        <a href="{{ request()->fullUrlWithQuery(['tab'=>$tab??'0','page' => $model->currentPage() + 1]) }}" class="page-link" aria-controls="history-table">next</a>
                    </li>
                @else
                    <li class="paginate_button page-item next disabled">
                        <a href="#" class="page-link" aria-controls="history-table">next</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>