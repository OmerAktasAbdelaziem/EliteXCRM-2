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
    <span class="section-title">General Settings</span>
    @if($isPipelineAdmin || $isSuperAdmin)
    <a href = "{{ route('settings.editLogo') }}"><span class="section-text">Edit logo</span></a>
    @endif
</div>        


<div class ="section-area">
    <span class="section-title">Ad Settings</span>
    @if($isPipelineAdmin || $isSuperAdmin || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'ads_list'))
    <a href = "{{ route('ads.index') }}"><span class="section-text">Google sheet linker</span></a>
    @endif
</div>    
<div class ="section-area" style="padding-top: calc(100px - 5vw);">
    <span class="section-title">Client Question Settings</span>
    @if($isSuperAdmin)
    <a href = "{{ route('question.index') }}"><span class="section-text">Client Questions</span></a>
    @endif
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