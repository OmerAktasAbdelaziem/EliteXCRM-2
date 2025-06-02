@extends("layouts.app")
@section("style")
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
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-wallet-alt me-1 font-22 text-danger"></i>
                                </div>
                                <h5 class="mb-0 text-danger">
                                    @if ($asset->getKey())
                                        Asset Group Edit
                                    @else
                                        Asset Group Registration
                                    @endif
                                </h5>
                            </div>
                            <hr>
                            <form class="row g-3" method="POST" action="{{ $asset->getKey()?route('asset.update',$asset->getKey()):route('asset.store') }}" enctype="multipart/form-data">
                                @csrf
                                @if ($asset->getKey())
                                    @method('PUT')
                                @endif
                                @if ($asset->img)
                                    <div class="col-12">
                                        <img src="{{url($asset->img)}}" style="width: 100px" />
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="input-group">
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $asset->name??old('name') }}" placeholder="Asset  Name" required />
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="symbol" class="form-label">Symbol</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="symbol" name="symbol" value="{{ $asset->symbol??old('symbol') }}" placeholder="Asset Symbol" required />
                                    </div>
                                    @error('symbol')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="currency" class="form-label">Base Currency</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="currency" name="currency" value="{{ $asset->currency??old('currency') }}" placeholder="Asset Base Currency" required />
                                    </div>
                                    @error('currency')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Category</label>
                                    <div class="input-group">
                                        <select id="category" class="single-select form-select" name="category">
                                            @foreach (['Forex','Crypto','Stocks','Indx','Commodity'] as $category)
                                                <option value="{{$category}}" @if ($asset->category == $category) selected @endif>{{$category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="img" class="form-label">Image</label>
                                    <input class="form-control" type="file" id="img" name="img" />
                                    @error('img')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-check form-switch form-check-primary">
                                        <label class="form-check-label" for="is_active">Active</label>
                                        <input class="form-check-input" type="checkbox" role="switch" value="1" id="is_active" name="is_active" @if ($asset->is_active || !$asset->getKey()) checked @endif >
                                    </div>
                                    @error('is_active')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    @if ($asset->getKey())
                                        <button type="submit" class="btn btn-danger px-5">Update</button>
                                    @else
                                        <button type="submit" class="btn btn-danger px-5">Register</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
@endsection