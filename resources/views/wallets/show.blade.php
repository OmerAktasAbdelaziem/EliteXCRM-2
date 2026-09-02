@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.944') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.944') }}" rel="stylesheet" />
    <style>
        .deposit-only {
            display: none;
        }
        .type-deposit .deposit-only {
            display: flex !important;
        }
        .field-row {
            flex-wrap: wrap;
        }
        .field-row .input-group {
            flex: 1 1 auto;
        }
        .field-value-group {
            display: flex;
            flex-wrap: wrap;
            /* gap: 0.25rem; */
            margin-top: 0.25rem;
            width: 100%;
        }
        .field-value-group .input-group {
            flex: 1 1 45%;
        }
        @media (max-width: 576px) {
            .field-value-group .input-group {
                flex: 1 1 100%;
            }
        }
    </style>
@endsection
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('fail'))
                        <div class="alert alert-danger">
                            {{ session('fail') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xl-9 mx-auto mt-2">
                    <div class="card border-top border-0 border-4 border-danger">
                        <div class="card-body p-5">
                            <div class="d-flex justify-content-between">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bx-shield-quarter me-1 font-22 text-danger"></i></div>
                                    <h5 class="mb-0 text-danger">
                                        @if ($wallet->getKey())
                                            Wallet Edit
                                        @else
                                            Wallet Registration
                                        @endif
                                    </h5>                                    
                                </div>
                                @if ($wallet->getKey() && ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermission($userAuth, 'wallet_delete')))
                                    <button type="button" class="btn btn-sm btn-danger"data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                @endif
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ $wallet->getKey()?route('wallets.update',$wallet->getKey()):route('wallets.store') }}">
                                @csrf
                                @if ($wallet->getKey())
                                    @method('PUT')
                                @endif

                                <div class="col-md-6">
                                    <label for="name_en" class="form-label">English Name</label>
                                    <div class="input-group">
                                        <input class="form-control" id="name_en" name="name_en" type="text" placeholder="Enter English name here..." required value="{{ old('name_en', $wallet->name_en) }}">
                                    </div>
                                    @error('name_en')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="name_ar" class="form-label">Arabic Name</label>
                                    <div class="input-group">
                                        <input class="form-control" id="name_ar" name="name_ar" type="text" placeholder="Enter Arabic name here..." required value="{{ old('name_ar', $wallet->name_ar) }}">
                                    </div>
                                    @error('name_ar')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <div class="input-group">
                                        <select class="form-select" name="type" id="wallet-type" @if ($wallet->getKey()) disabled @endif>
                                            <option value="deposit" {{ (old('type', $wallet->type) == 'deposit') ? 'selected' : '' }}>
                                                Deposit
                                            </option>
                                            <option value="withdrawal" {{ (old('type', $wallet->type) == 'withdrawal') ? 'selected' : '' }}>
                                                Withdrawal
                                            </option>
                                        </select>
                                    </div>
                                    @if ($wallet->getKey())
                                        {{-- <p class="form-select">{{$wallet->type}}</p> --}}
                                        <p class="small text-muted">type can't be changed</p>
                                    @endif

                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <div class="col-md-6">
                                    <label for="address" class="form-label">Address</label>
                                    <div class="input-group">
                                        <input class="form-control" id="address" name="address" type="text" placeholder="Enter address here..." required value="{{ old('address', $wallet->address) }}">
                                    </div>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="network" class="form-label">Network</label>
                                    <div class="input-group">
                                        <input class="form-control" id="network" name="network" type="text" placeholder="Enter network here..." required value="{{ old('network', $wallet->network) }}">
                                    </div>
                                    @error('network')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                <div class="col-6">
                                    <label class="form-label">Countries</label>
                                    <div class="input-group">
                                        <select class="multiple-select form-select" name="countries[]" multiple>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ in_array($country->id, old('countries', $wallet->countries->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                                    {{ $country->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('countries')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-bold mb-0">Wallet Custom Fields</label>
                                        <button type="button" class="btn btn-outline-success btn-sm px-3" onclick="addNewWalletField()">
                                            <i class="bx bx-plus me-1"></i> Add Custom Field
                                        </button>
                                    </div>
                                    
                                    <div id="fields-wrapper" class="d-flex flex-column gap-2 mb-2">
                                        @php
                                            $oldEnglish = old('english_field_names', []);
                                            $oldArabic = old('arabic_field_names', []);
                                            $oldEnglishVals  = old('english_field_values', []);
                                            $oldArabicVals   = old('arabic_field_values', []);
                                            $hasOldInput = count($oldEnglish) > 0;
                                            
                                            $loopItems = $hasOldInput ? $oldEnglish : ($wallet->fields ?? []);
                                        @endphp

                                        @forelse($loopItems as $index => $item)
                                            @php
                                                $englishValue = $hasOldInput ? $item : $item->english_field_name;
                                                $arabicValue = $hasOldInput ? $oldArabic[$index] : $item->arabic_field_name;
                                                $englishVal = $hasOldInput ? ($oldEnglishVals[$index] ?? '') : ($item->english_field_value ?? '');
                                                $arabicVal  = $hasOldInput ? ($oldArabicVals[$index] ?? '') : ($item->arabic_field_value ?? '');
                                            @endphp
                                            <div class="field-row">
                                                @if (!$loop->first)
                                                    <hr>
                                                @endif
                                                <div class="fields-group d-flex gap-1">
                                                    <div class="field-row input-group">
                                                        <span class="input-group-text bg-light text-muted small fw-bold">EN</span>
                                                        <input type="text" class="form-control" name="english_field_names[]" value="{{ $englishValue }}" placeholder="Field Name (English)">
                                                        
                                                        <span class="input-group-text bg-light text-muted small fw-bold">AR</span>
                                                        <input type="text" class="form-control text-end" dir="rtl" name="arabic_field_names[]" value="{{ $arabicValue }}" placeholder="Field Name (Arabic)">
                                                        
                                                        {{-- Value inputs – only for deposit --}}
                                                        <div class="field-value-group deposit-only input-group">
                                                            <span class="input-group-text bg-light text-muted small fw-bold">EN Value</span>
                                                            <input type="text" class="form-control" name="english_field_values[]" value="{{ $englishVal }}" placeholder="Value (English)">
                                                            <span class="input-group-text bg-light text-muted small fw-bold">AR Value</span>
                                                            <input type="text" class="form-control text-end" dir="rtl" name="arabic_field_values[]" value="{{ $arabicVal }}" placeholder="Value (Arabic)">
                                                        </div>
                                                    </div>

                                                    <button type="button" class="btn btn-outline-danger" onclick="removeWalletField(this)">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <!-- Default Single Empty Row if no data exists -->
                                            
                                            <div class="field-row">
                                                <div class="fields-group d-flex gap-1">
                                                    <div class="field-row input-group">
                                                        <span class="input-group-text bg-light text-muted small fw-bold">EN</span>
                                                        <input type="text" class="form-control" name="english_field_names[]" placeholder="Field Name (English)">
                                                        
                                                        <span class="input-group-text bg-light text-muted small fw-bold">AR</span>
                                                        <input type="text" class="form-control text-end" dir="rtl" name="arabic_field_names[]" placeholder="Field Name (Arabic)">
            
                                                        <div class="field-value-group deposit-only input-group">
                                                            <span class="input-group-text bg-light text-muted small fw-bold">EN Value</span>
                                                            <input type="text" class="form-control" name="english_field_values[]" placeholder="Value (English)">
                                                            <span class="input-group-text bg-light text-muted small fw-bold">AR Value</span>
                                                            <input type="text" class="form-control text-end" dir="rtl" name="arabic_field_values[]" placeholder="Value (Arabic)">
                                                        </div>
                                                    </div>
                                                    
                                                    <button type="button" class="btn btn-outline-danger" onclick="removeWalletField(this)">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>    

                                    @if($errors->has('english_field_names.*') || $errors->has('arabic_field_names.*') || $errors->has('english_field_values.*') || $errors->has('arabic_field_values.*'))
                                        <div class="text-danger small mt-1">Please ensure all field entries are filled correctly.</div>
                                    @endif
                                </div>



                                <div class="col-12">
                                    @if ($wallet->getKey())
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermission($userAuth, 'wallet_edit'))
                                            <button type="submit" class="btn btn-danger px-5">Update</button>
                                        @endif
                                    @else
                                        @if ($isSuperAdmin || $isPipelineAdmin || UserPermission::hasPermission($userAuth, 'wallet_create'))
                                            <button type="submit" class="btn btn-danger px-5">Register</button>
                                        @endif
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Are you sure you want to delete selected asset from this Wallet?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form method="post" action="{{ route('wallets.delete', $wallet->id ?? 0) }}">
                @csrf
                <input type="hidden" name="_method" value="delete">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
        </div>
    </div>
</div>

@endsection

@section("script")
    <script>
        function addNewWalletField() {
            let wrapper = document.getElementById('fields-wrapper');

            const rows = document.querySelector('.field-row');
            let hr = '<hr>';
            if(!rows){
                hr = '';
            }

            let div = document.createElement('div');
            div.className = 'field-row';

            let isDeposit = document.getElementById('wallet-type').value === 'deposit';
            
            let valueHtml = isDeposit ? `
                <div class="field-value-group deposit-only input-group" style="display:flex !important;">
                    <span class="input-group-text bg-light text-muted small fw-bold">EN Value</span>
                    <input type="text" class="form-control" name="english_field_values[]" placeholder="Value (English)">
                    <span class="input-group-text bg-light text-muted small fw-bold">AR Value</span>
                    <input type="text" class="form-control text-end" dir="rtl" name="arabic_field_values[]" placeholder="Value (Arabic)">
                </div>
            ` : `
                <div class="field-value-group deposit-only input-group" style="display:none;">
                    <span class="input-group-text bg-light text-muted small fw-bold">EN Value</span>
                    <input type="text" class="form-control" name="english_field_values[]" placeholder="Value (English)">
                    <span class="input-group-text bg-light text-muted small fw-bold">AR Value</span>
                    <input type="text" class="form-control text-end" dir="rtl" name="arabic_field_values[]" placeholder="Value (Arabic)">
                </div>
            `;

            div.innerHTML = `
                ${hr}
                
                <div class="fields-group d-flex gap-1">
                    <div class="field-row input-group">
                        <span class="input-group-text bg-light text-muted small fw-bold">EN</span>
                        <input type="text" class="form-control" name="english_field_names[]" placeholder="Field Name (English)">
                        
                        <span class="input-group-text bg-light text-muted small fw-bold">AR</span>
                        <input type="text" class="form-control text-end" dir="rtl" name="arabic_field_names[]" placeholder="Field Name (Arabic)">
                        
                        ${valueHtml}
                    </div>
                                                
                    <button type="button" class="btn btn-outline-danger" onclick="removeWalletField(this)">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            `;

            wrapper.appendChild(div);
            div.querySelector('input[name="english_field_names[]"]').focus();
        }

        function removeWalletField(button) {
            let row = button.closest('.field-row');
            row.remove();
        }

        // Toggle visibility of value fields based on type selection
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('wallet-type');
            const allRows = document.querySelectorAll('.field-row');
            
            function toggleValueFields(show) {
                document.querySelectorAll('.field-row .field-value-group').forEach(group => {
                    group.style.display = show ? 'flex !important' : 'none';
                });
                // Also toggle the container class to allow CSS override
                const form = typeSelect.closest('form');
                if (show) {
                    form.classList.add('type-deposit');
                } else {
                    form.classList.remove('type-deposit');
                }
            }
            
            // Initial state
            toggleValueFields(typeSelect.value === 'deposit');
            
            // On change
            typeSelect.addEventListener('change', function() {
                toggleValueFields(this.value === 'deposit');
            });
        });
    </script>

    <script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/table-datatable.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.944') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.944') }}"></script>
@endsection