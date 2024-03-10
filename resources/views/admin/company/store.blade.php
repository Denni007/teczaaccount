@extends('admin.app-admin')
@section('title') Company @endsection
@section('page-header')
@endsection
@section('content')
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                  Company {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href={{ route('admin.company') }}></a> Company</li>
                  <li class="breadcrumb-item active">Company {{ isset($dataArr) ? "Edit" : "Add" }}</li>
               </ol>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>
   <section class="content">
      <div class="row d-flex justify-content-center">
         <div class="col-md-10">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Company</h3>
               </div>
               <div class="card-body">
                  <form class="add-company-form" action="{{ route('admin.company.store') }}" method="POST">
                     @csrf
                     @method("POST")
                     <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="companyName">Company Name <span style="color: red">*</span></label>
                           <input type="text" name="company_name"
                              value="{{ $dataArr['company_name'] ?? old('company_name') }}" id="companyName"
                              class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="inputEmail">Email id <span style="color: red">*</span></label>
                           <input id="inputEmail" type="email" name="email_id"
                              value="{{ $dataArr['email_id'] ?? old('email_id') }}" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="inputContact">Contact No. <span style="color: red">*</span></label>
                           <input id="inputContact" type="text" name="contact_no"
                              value="{{  $dataArr['contact_no'] ?? old('contact_no') }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="inputmobile">Mobile No. <span style="color: red">*</span></label>
                           <input type="text" id="inputmobile" name="mobile_no"
                              value="{{ $dataArr['mobile_no'] ?? old('mobile_no') }}" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="inputcontactno">Contact Person Name <span style="color: red">*</span></label>
                           <input type="text" name="contact_person_name"
                              value="{{ $dataArr['contact_person_name'] ?? old('contact_person_name') }}"
                              id="inputcontactno" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="inputgst_no">GST No.</label>
                           <input type="text" name="gst_no" value="{{ $dataArr['gst_no'] ?? old('gst_no') }}"
                              id="inputgst_no" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="address">Address <span style="color: red">*</span></label>
                           <textarea id="address" name="address" value="{{ $dataArr['address'] ?? old('address') }}"
                              class="form-control" rows="3"><?=$dataArr['address'] ?? old("address") ?></textarea>
                        </div>
                        <div class="col-6 form-group">
                           <label for="country">Country <span style="color: red">*</span></label>
                           <select class="form-control select2" id="country" name="country" onchange="fetchState();">
                              <option value="">Select Country</option>
                              @foreach($country as $co)
                                 <option value="{{$co->id}}" @if(isset($dataArr['country_id']) && $dataArr['country_id'] == $co->id) selected @endif>{{$co->name}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="state">State <span style="color: red">*</span></label>
                           <select class="form-control select2" name="state" id="state">
                              <option value="">Select State</option>
                           </select>
                        </div>
                        <div class="col-6 form-group">
                           <label for="pincode">Pincode <span style="color: red">*</span></label>
                           <input type="text" name="pincode" value="{{ $dataArr['pincode'] ?? old('pincode') }}"
                              id="pincode" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6">
                           <label for="cin">Cin</label>
                           <input type="text" name="cin" value="{{ $dataArr['cin'] ?? old('cin') }}" id="cin"
                              class="form-control">
                        </div>
                        <div class="col-6">
                           <label for="website">Website</label>
                           <input type="url" name="website" value="{{  $dataArr['website'] ?? old('website') }}"
                              id="website" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6">
                           <label for="currency">Currency <span style="color: red">*</span></label>
                           <input type="text" name="currency" value="{{ $dataArr['currency'] ?? old('currency') }}"
                              id="currency" class="form-control">
                        </div>
                        <div class="col-6">
                           <label for="pan_no">PAN No <span style="color: red">*</span></label>
                           <input type="text" name="pan_no" value="{{ $dataArr['pan_no'] ?? old('pan_no') }}"
                              id="pan_no" class="form-control">
                        </div>
                        
                     </div>
                     <div class="row">
                        <div class="col-6">
                           <label for="pan_no">Company Sufix <span style="color: red">*</span></label>
                           <input type="text" name="sufix" value="{{ $dataArr['sufix'] ?? old('sufix') }}"
                              id="sufix" class="form-control">
                        </div>
                        <div class="col-6">
                           <label for="pan_no">Wallet Balance <span style="color: red">*</span></label>
                           <input type="text" name="wallet_balance" value="{{ $dataArr['wallet_balance'] ?? old('wallet_balance') }}"
                              id="wallet_balance" class="form-control">
                        </div>
                     </div>
                     <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                           {{-- <input type="submit" value="Save" class="btn btn-success"> --}}
                           <button type="submit" class="btn btn-success">
                              {{ isset($dataArr) ? 'Update' : 'save' }}
                           </button>
                        </div>
                     </div>
                  </form>
                  {{-- @endif --}}
               </div>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->
         </div>
      </div>
   </section>
@endsection
@section('footer_content')
<script src="{{ asset('js/pages/company/add_company.js') }}"></script>
<script type="text/javascript">
   $(document).ready(function (){
      fetchState();
   });
   function fetchState()
   {
      var country = $('#country').val();
      if(country != '')
      {
         var csrfToken = '{{ csrf_token() }}';
         var url = "{{route('fetch-state')}}";
         jQuery.ajax({
           url:  url,
           type: "POST",
           data: {
               '_token' : csrfToken,
               'country': country
           },
           success: function(response)
           {
               if(response.status == 1)
               {
                  $('#state').html(response.html);
                  @if(isset($dataArr['state_id']))
                     $('#state').val('{{$dataArr["state_id"]}}');
                  @endif
               }
           }
       });
      }
   }
</script>
@endsection