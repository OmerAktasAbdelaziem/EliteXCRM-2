@extends("layouts.app")

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
<div class="row">
     <div class="col-xl-9 mx-auto mt-2">
                <div class="card border-top border-0 border-4 border-danger">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bx-layer me-1 font-22 text-danger"></i>
                            </div>
                            <h5 class="mb-0 text-danger">
                                Settings
                            </h5>
                        </div>
                        <hr>
                    
                        <div class="row">
                           
    <div class="col-md-12">
<div class ="section-area">
    <span class="section-title">Add New Logo</span>
    <form action="{{ route('settings.uploadLogo') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        @if(isset(Auth::User()->pipeline->logo) && Auth::User()->pipeline->logo != null)
        <img class="logo-img" src="{{ asset('storage/'.Auth::User()->pipeline->logo) }}" alt="Logo">
        @endif
    </div>
    <div class="row">
    <div class="col-md-6">
    <label>Upload Image:</label>
    <input type="file" name="logo" accept="image/*" required>
    </div>
    </div>
    <button type="submit">Upload</button>
</form>
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