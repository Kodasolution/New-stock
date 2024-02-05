@extends('layouts/layoutMaster')

@section('title', ' Edit category')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

{{--  @section('page-script')
<script src="{{asset('assets/js/form-layouts.js')}}"></script>
@endsection  --}}

@section('content')

@if (session()->has('success'))
        <div class=" alert alert-success ">
            {{ session()->get('success') }}
        </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif


<h4 class="py-3 breadcrumb-wrapper mb-4"><span class="text-muted fw-light"><a href="{{ route('category.index') }}">Liste des categories</a></h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Modifier une categorie</h5> 
        {{--  <small class="text-muted float-end">Default label</small>  --}}
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('category.update', $category->id) }}" >
            @csrf
            @method('patch')
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Name</label>
            <div class="col-sm-10">
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
              id="basic-default-name" placeholder="Enter category name" value="{{ $category->name }}" />
              @error('name')
                  <div class="invalid-feedback">{{ $errors->first('name') }}</div>
              @enderror
            </div>
          </div>

          {{--  <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="multicol-country">Category</label>
            <div class="col-sm-10">
              <select  class="form-select @error('parent_id') is-invalid @enderror" data-allow-clear="true" name="parent_id" >
                  @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"{{ $cat->id == $category->parent_id ? ' selected' : '' }}>
                        @if ($cat->parent)
                            @php
                                $parent = $cat->parent;
                                $parentNames = [];
                                while ($parent) {
                                    $parentNames[] = $parent->name;
                                    $parent = $parent->parent;
                                }
                                echo implode(' &rarr; ', array_reverse($parentNames)) . ' &rarr; ';
                            @endphp
                        @endif
                        {{ $cat->name }}
                    </option>
                  @endforeach
                  <option value=""{{ is_null($category->parent_id) ? ' selected' : '' }}>No Parent</option>
              </select>
              @error('parent_id')
                  <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
              @enderror
            </div>
          </div>  --}}

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="multicol-country">Status</label>
            <div class="col-sm-10">
              <select class="form-select @error('status_cat') is-invalid @enderror" data-allow-clear="true" name="status_cat" >
                @if($category->status_cat)
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                @else
                    <option value="1">Active</option>
                    <option value="0" selected>Inactive</option>
                @endif
              </select>
              @error('status_cat')
                  <div class="invalid-feedback">{{ $errors->first('status_cat') }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
