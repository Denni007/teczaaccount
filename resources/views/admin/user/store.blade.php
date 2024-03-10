@extends('admin.app-admin')
@section('title') User @endsection
@section('page-header')
@endsection
@section('content')

   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                  User {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.user') }}"></a> User</li>
                  <li class="breadcrumb-item active">User {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                  <h3 class="card-title">User</h3>
               </div>
               <div class="card-body">
                  <form class="add-user-form" action="{{ route('admin.user.store') }}" method="POST">
                     @csrf
                     @method("POST")
                     <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="name">Name <span style="color: red">*</span></label>
                           <input type="text" name="name"
                              value="{{ $dataArr['name'] ?? old('name') }}" id="name"
                              class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="inputEmail">Email<span style="color: red">*</span></label>
                           <input id="inputEmail" type="email" name="email"
                              value="{{ $dataArr['email'] ?? old('email') }}" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="inputContact">Mobile No.<span style="color: red">*</span></label>
                           <input id="inputContact" type="text" name="phone"
                              value="{{  $dataArr['phone'] ?? old('phone') }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="user_type">User Type <span style="color: red">*</span></label>
                           <select class="form-control select2" name="user_type" id="user_type">
                              <option value="">Select User Type</option>
                              <option value="1" @if(isset($dataArr['user_type']) && $dataArr['user_type'] == 1) selected @endif>Cash Login</option>
                              <option value="2" @if(isset($dataArr['user_type']) && $dataArr['user_type'] == 2) selected @endif>Cash & Bill Login</option>
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="address">Address <span style="color: red">*</span></label>
                           <textarea id="address" name="address" value="{{ $dataArr['address'] ?? old('address') }}"
                              class="form-control" rows="3"><?=$dataArr['address'] ?? old("address") ?></textarea>
                        </div>
                        <div class="col-6 form-group">
                           <label for="country">Designation <span style="color: red">*</span></label>
                           <input type="text" name="designation" value="{{ $dataArr['designation'] ?? old('designation') }}"
                              id="designation" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="state">Shift Type <span style="color: red">*</span></label>
                           <select class="form-control select2" name="shift_type" id="shift_type">
                              <option value="">Select Shift</option>
                              <option value="1" @if(isset($dataArr['shift_type']) && $dataArr['shift_type'] == 1) selected @endif>Day</option>
                              <option value="2" @if(isset($dataArr['shift_type']) && $dataArr['shift_type'] == 2) selected @endif>Night</option>
                              <option value="3" @if(isset($dataArr['shift_type']) && $dataArr['shift_type'] == 3) selected @endif>Full Time</option>
                              <option value="4" @if(isset($dataArr['shift_type']) && $dataArr['shift_type'] == 4) selected @endif> Daily Worker</option>
                           </select>
                        </div>
                        <div class="col-6 form-group">
                           <label for="country">Password <span style="color: red">*</span></label>
                           <input type="text" name="text_pass" value="{{ $dataArr['text_pass'] ?? old('text_pass') }}"
                              id="text_pass" class="form-control">
                        </div>
                     </div>
                     <h3>Bank Details</h3>
                     <div class="row">
                        <div class="col-6">
                           <label for="">Bank Name</label>
                           <input type="text" name="bank_name" class="form-control" id="bank_name" placeholder="Bank Name"  value="{{ $dataArr['bank_name'] ?? old('bank_name') }}">
                        </div>
                        <div class="col-6">
                           <label for="ifsc_code">IFSC Code</label>
                           <input type="text" name="ifsc_code" class="form-control" id="ifsc_code" placeholder="IFSC Code" value="{{ $dataArr['ifsc_code'] ?? old('ifsc_code') }}">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6">
                           <label for="swift_code">Swift Code</label>
                           <input type="text" name="swift_code" class="form-control" id="swift_code" placeholder="Swift Code" value="{{ $dataArr['swift_code'] ?? old('swift_code') }}">
                        </div>
                        <div class="col-6">
                           <label for="beneficary_name">Beneficary Name</label>
                           <input type="text" name="beneficary_name" class="form-control" id="beneficary_name" placeholder="Beneficary Name" value="{{ $dataArr['beneficary_name'] ?? old('beneficary_name') }}">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6">
                           <label for="account_no">Account No.</label>
                           <input type="text" name="account_no" class="form-control" id="account_no" placeholder="Account No." value="{{ $dataArr['account_no'] ?? old('account_no') }}">
                        </div>
                        <div class="col-6">
                           <label for="account_type">Account Type</label>
                            <input type="text" name="account_type" class="form-control" id="account_type" placeholder="Account Type" value="{{ $dataArr['account_type'] ?? old('account_type') }}">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6">
                           <label for="branch_name">Branch Name</label>
                           <input type="text" name="branch_name" class="form-control" id="branch_name" placeholder="Branch Name" value="{{ $dataArr['branch_name'] ?? old('branch_name') }}">
                        </div>
                     </div>
                     <h3>Permissions</h3>
                     <div class="row">
                        <div class="col-4">
                           <label>Production</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[production_add]" @if(isset($permission) && in_array('production_add',$permission)) checked @endif>
                                <label class="form-check-label">Add/Edit</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[production_list]" @if(isset($permission) && in_array('production_list',$permission)) checked @endif>
                                <label class="form-check-label">List</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[production_delete]" @if(isset($permission) && in_array('production_delete',$permission)) checked @endif>
                                <label class="form-check-label">Delete</label>
                              </div>
                           </div>
                         </div>
                         <div class="col-4">
                           <label>Product</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[product_add]" @if(isset($permission) && in_array('product_add',$permission)) checked @endif>
                                <label class="form-check-label">Add/Edit</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[product_list]" @if(isset($permission) && in_array('product_list',$permission)) checked @endif>
                                <label class="form-check-label">List</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[product_delete]" @if(isset($permission) && in_array('product_delete',$permission)) checked @endif>
                                <label class="form-check-label">Delete</label>
                              </div>
                           </div>
                         </div>
                         <div class="col-4">
                           <label>Raw Material</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[raw_material_add]" @if(isset($permission) && in_array('raw_material_list',$permission)) checked @endif>
                                <label class="form-check-label">Add/Edit</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[raw_material_list]" @if(isset($permission) && in_array('production_delete',$permission)) checked @endif>
                                <label class="form-check-label">List</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[raw_material_delete]" @if(isset($permission) && in_array('raw_material_delete',$permission)) checked @endif>
                                <label class="form-check-label">Delete</label>
                              </div>
                            </div>
                         </div>
                         <div class="col-4">
                           <label>Invoice</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[invoice_add]" @if(isset($permission) && in_array('invoice_add',$permission)) checked @endif>
                                <label class="form-check-label">Add/Edit</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[invoice_list]" @if(isset($permission) && in_array('invoice_list',$permission)) checked @endif>
                                <label class="form-check-label">List</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[invoice_delete]" @if(isset($permission) && in_array('invoice_delete',$permission)) checked @endif>
                                <label class="form-check-label">Delete</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[invoice_approve]" @if(isset($permission) && in_array('invoice_approve',$permission)) checked @endif>
                                <label class="form-check-label">Approvel</label>
                              </div>
                            </div>
                         </div>
                         <div class="col-4">
                           <label>Vendor</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[vendor_add]" @if(isset($permission) && in_array('vendor_add',$permission)) checked @endif>
                                <label class="form-check-label">Add/Edit</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[vendor_list]" @if(isset($permission) && in_array('vendor_list',$permission)) checked @endif>
                                <label class="form-check-label">List</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[vendor_delete]" @if(isset($permission) && in_array('vendor_delete',$permission)) checked @endif>
                                <label class="form-check-label">Delete</label>
                              </div>
                            </div>
                         </div>
                         <div class="col-4">
                           <label>Payment</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[payment_add]" @if(isset($permission) && in_array('payment_add',$permission)) checked @endif>
                                <label class="form-check-label">Add/Edit</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[payment_list]" @if(isset($permission) && in_array('payment_list',$permission)) checked @endif>
                                <label class="form-check-label">List</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[payment_delete]" @if(isset($permission) && in_array('payment_delete',$permission)) checked @endif>
                                <label class="form-check-label">Delete</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[payment_approve]" @if(isset($permission) && in_array('payment_approve',$permission)) checked @endif>
                                <label class="form-check-label">Approvel</label>
                              </div>
                            </div>
                         </div>
                         <div class="col-4">
                           <label>Expense</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[expense_add]" @if(isset($permission) && in_array('expense_add',$permission)) checked @endif>
                                <label class="form-check-label">Add/Edit</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[expense_list]" @if(isset($permission) && in_array('expense_list',$permission)) checked @endif>
                                <label class="form-check-label">List</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[expense_delete]" @if(isset($permission) && in_array('expense_delete',$permission)) checked @endif>
                                <label class="form-check-label">Delete</label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[expense_delete]" @if(isset($permission) && in_array('expense_delete',$permission)) checked @endif>
                                <label class="form-check-label">Approvel</label>
                              </div>
                            </div>
                         </div>
                         <div class="col-4">
                           <label>Wallet</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[wallet_transfer]" @if(isset($permission) && in_array('wallet_transfer',$permission)) checked @endif>
                                <label class="form-check-label">Tarnsfer</label>
                              </div>
                            </div>
                         </div>
                         <div class="col-4">
                           <label>Account</label>
                           <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[account_details]" @if(isset($permission) && in_array('account_details',$permission)) checked @endif>
                                <label class="form-check-label">Details</label>
                              </div>
                            </div>
                         </div>
                        <!-- <div class="col-6">
                           <div class="row">
                              <label>Production</label>
                           </div>
                           <div class="row">
                              <input type="checkbox"  name="production_add"><label class="form-check-label">Add/Edit</label>
                           </div>
                           <div class="row">
                              <input type="checkbox"  name="production_list">List
                           </div>
                           <div class="row">
                              <input type="checkbox"  name="production_delete">Delete
                           </div>
                        </div> -->
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
<script src="{{ asset('js/pages/user/add_user.js') }}"></script>
@endsection