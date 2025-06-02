@extends("layouts.app")
@section("style")

@endsection

@section("wrapper")
    <div class="page-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-xl-12 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1">Settings</h5>
                                    </div>
                                    <div class="font-22 ms-auto"><i class='bx bx-dots-horizontal-rounded'></i>
                                    </div>
                                </div>
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#AllContact" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class="bx bx-user-circle font-18 me-1"></i>
                                                </div>
                                                <div class="tab-title">Avatars</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade active show" id="AllContact" role="tabpanel">
                                        <form name="avatarform" id="avatarform" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                        <div class="row">
                                            <div class="col-lg-6 text-center">
                                                <img alt="Admin" src="{{  Storage::disk('local')->url($system->malePic) }}" class="rounded-circle p-1 bg-primary text-white fs-1 mt-1" width="100" >
                                                <div class="col-12">
                                                    <div class="w-50 mx-auto">
                                                        <label for="formmaleAvatar" class="form-label">Upload Male Avatar</label>
                                                        <input class="form-control" type="file" name="maleavatar" form="avatarform" id="formmaleAvatar" accept="image/*">
                                                        @error('maleavatar')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-center">
                                                <img alt="Admin" src="{{  Storage::disk('local')->url($system->femalePic) }}" class="rounded-circle p-1 bg-primary text-white fs-1 mt-1" width="100" >
                                                <div class="col-12">
                                                    <div class="w-50 mx-auto">
                                                        <label for="formfemaleAvatar" class="form-label">Upload Female Avatar</label>
                                                        <input class="form-control" type="file" name="femaleavatar" form="avatarform" id="formfemaleAvatar" accept="image/*">
                                                        @error('femaleavatar')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-12">
                                                <button type="submit" form="avatarform" class="btn btn-success px-5">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section("script")

@endsection
