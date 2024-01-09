@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
            </div>
        </div>
    </header>
<!-- Main page content-->
<div class="container-xl px-4 mt-n10">       
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      {{-- <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>    </h1>
          </div>
        </div>
      </div><!-- /.container-fluid --> --}}
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List of Pallet</h3>
              </div>
              
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="mb-3 col-sm-6">
                  <form action="{{ url('/pallet/search') }}" method="POST" id="searchForm">
                    @csrf <!-- Include CSRF token for security -->
                  <!-- Search Form -->
                  <div class="input-group input-group-sm">
                      <select class="form-control" name="searchBy" id="searchBy" onchange="toggleSearchInputs()">
                        <option value="date">Date</option>
                          <option value="no_pallet">No Pallet</option>
                          
                      </select>
                      <input name="palletNo" type="text" class="form-control" id="searchNoPallet" placeholder="Enter search term" style="display: none;">
                      <input name="dateFrom" type="date" class="form-control" id="startDate" placeholder="Start Date"> <input name="dateTo" type="date" class="form-control" id="endDate" placeholder="End Date">
                      <button class="btn btn-dark btn-sm" type="submit" onclick="search()">Search</button>
                  </div>
                </form>
              </div>
            </div>
              <div class="row">
              
              <script>
                function toggleSearchInputs() {
                    var searchBy = document.getElementById('searchBy').value;
                    var searchNoPallet = document.getElementById('searchNoPallet');
                    var startDate = document.getElementById('startDate');
                    var endDate = document.getElementById('endDate');
            
                    if (searchBy === 'no_pallet') {
                        searchNoPallet.style.display = 'block';
                        startDate.style.display = 'none';
                        endDate.style.display = 'none';
                    } else if (searchBy === 'date') {
                        searchNoPallet.style.display = 'none';
                        startDate.style.display = 'inline-block';
                        endDate.style.display = 'inline-block';
                    } else {
                        searchNoPallet.style.display = 'none';
                        startDate.style.display = 'none';
                        endDate.style.display = 'none';
                    }
                }
            
                function search() {
                    // Implement your search logic here based on selected option and input values
                    var searchBy = document.getElementById('searchBy').value;
                    var searchTerm;
            
                    if (searchBy === 'no_pallet') {
                        searchTerm = document.getElementById('searchNoPallet').value;
                    } else if (searchBy === 'date') {
                        var startDate = document.getElementById('startDate').value;
                        var endDate = document.getElementById('endDate').value;
                        // Implement logic to use startDate and endDate for date search
                    }
            
                    // Perform search operation using searchTerm
                    console.log('Search By:', searchBy, 'Search Term:', searchTerm);
                }
            </script>
                    <div class="col-sm-12">
                      <!--alert success -->
                      @if (session('status'))
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('status') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div> 
                    @endif

                    @if (session('failed'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>{{ session('failed') }}</strong>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> 
                  @endif
                    
                      <!--alert success -->
                      <!--validasi form-->
                        @if (count($errors)>0)
                          <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              <ul>
                                  <li><strong>Data Process Failed !</strong></li>
                                  @foreach ($errors->all() as $error)
                                      <li><strong>{{ $error }}</strong></li>
                                  @endforeach
                              </ul>
                          </div>
                        @endif
                      <!--end validasi form-->
                    </div>
            
              
                <div class="table-responsive">
                  <div class="mb-3 col-sm-6">
                    <!-- Search Form -->
          
                    <button type="button" class="btn btn-dark btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-add">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <button title="Import Asset" type="button" class="btn btn-info btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-import">
                        Import Assets
                    </button>

                      <script>
                        // JavaScript to toggle between input types based on the selected option
                        document.getElementById('searchType').addEventListener('change', function () {
                            var selectedType = this.value;

                            if (selectedType === 'date') {
                                document.getElementById('searchInputContainer').style.display = 'none';
                                document.getElementById('dateInputContainer').style.display = 'block';
                            } else {
                                document.getElementById('searchInputContainer').style.display = 'block';
                                document.getElementById('dateInputContainer').style.display = 'none';
                            }
                        });
                      </script>

                        <!-- Modal -->
                          <div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-add-label">Add Pallet</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('/pallet/store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group mb-3">
                                                <input type="text" class="form-control" id="no_delivery" name="no_delivery" placeholder="Enter No. Delivery" required>
                                            </div>
                                            <div class="form-group mb-3" id="noPalletContainer">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="no_pallet[]" placeholder="Enter No. Pallet" required>
                                                    <button class="btn btn-outline-dark" type="button" onclick="addNoPalletField()">+</button>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <select name="type_pallet" id="type_pallet" class="form-control">
                                                    <option value="">- Please Select Type Pallet -</option>
                                                    @foreach ($typePallet as $type)
                                                    <option value="{{ $type->name_value }}">{{ $type->name_value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <input type="date" class="form-control" id="date" name="date" placeholder="Enter Dropdown Category" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <select name="destination" id="destination" class="form-control">
                                                    <option value="">- Please Select Destination -</option>
                                                    @foreach ($destinationPallet as $des)
                                                    <option value="{{ $des->name_value }}">{{ $des->name_value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          </div>

                            <script>
                              function addNoPalletField() {
                                  var container = document.getElementById('noPalletContainer');
                                  var inputGroup = document.createElement('div');
                                  inputGroup.className = 'input-group mb-3';
                                  inputGroup.innerHTML = '<input type="text" class="form-control" name="no_pallet[]" placeholder="Enter No. Pallet" required>' +
                                      '<button class="btn btn-outline-dark" type="button" onclick="removeNoPalletField(this)">-</button>';
                                  container.appendChild(inputGroup);
                              }

                              function removeNoPalletField(button) {
                                  var container = document.getElementById('noPalletContainer');
                                  container.removeChild(button.parentNode);
                              }
                            </script>


                        <div class="modal fade" id="modal-import" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title" id="modal-add-label">Import Asset</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <form action="{{ url('/pallet/import') }}" method="POST" enctype="multipart/form-data">
                                      @csrf
                                      <div class="modal-body">
                                          <div class="mb-3">
                                              <input type="file" class="form-control" id="csvFile" name="excel-file" accept=".csv">
                                              <p class="text-danger">*file must be xlsx</p>
                                          </div>

                                          @error('excel-file')
                                              <div class="alert alert-danger" role="alert">
                                                  {{ $message }}
                                              </div>
                                          @enderror
                                      </div>
                                      <div class="modal-footer">
                                          <a href="{{ url('/pallet/download/format') }}" class="btn btn-link">
                                              Download Excel Format
                                          </a>
                                          <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                          <button type="submit" class="btn btn-primary">Submit</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                </div> 
                <script>
                  $(document).ready(function () {
                      var table = $("#tableUser").DataTable({
                          "responsive": true,
                          "lengthChange": false,
                          "autoWidth": false,
                          "order": [],
                          "dom": 'Bfrtip',
                          "buttons": [{
                              title: 'Pallet Tracing',
                              text: '<i class="fas fa-file-excel"></i> Export to Excel',
                              extend: 'excel',
                              className: 'btn btn-success btn-sm mb-2'
                          }]
                      });
                  });
              </script>
                <table id="tableUser" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>No. Delivery</th>
                    <th>No. Pallet</th>
                    <th>Type Pallet</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                      $no=1;
                    @endphp
                    @foreach ($palletData as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data->no_delivery }}</td>
                        <td>{{ $data->no_pallet }}</td>
                        <td>{{ $data->type_pallet }}</td>
                        <td>{{ $data->destination }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}</td>
                        <td>
                          @if($data->status == 1)
                              <!-- Button for active status -->
                              <button class="btn btn-success btn-sm">
                                  New
                              </button>
                          @else
                              <!-- Button for disposal status -->
                              <button class="btn btn-danger btn-sm">
                                  <i class="fa-solid fa-x"></i> Expired
                              </button>
                          @endif
                        </td>
                        <td>
                            <button title="Edit Pallet" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-update{{ $data->id }}">
                                <i class="fas fa-edit"></i>
                              </button>
                            <button title="Info Pallet" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-detail{{ $data->id }}">
                              <i class="fas fa-info"></i>
                          </button>
                            <button title="Delete Pallet" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                <i class="fas fa-trash-alt"></i>
                              </button>   
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modal-detail{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Details for No. Pallet: {{ $data->no_pallet }}</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <!-- Display Details Here -->
                                  @foreach($palletData->where('no_pallet', $data->no_pallet) as $pallet)
                                      <p>No. Delivery: {{ $pallet->no_delivery }}</p>
                                      <p>No. Pallet: {{ $pallet->no_pallet }}</p>
                                      <p>Type Pallet: {{ $pallet->type_pallet }}</p>
                                      <p>Destination: {{ $pallet->destination }}</p>
                                      <p>Date: {{ \Carbon\Carbon::parse($pallet->date)->format('d-m-Y') }}</p>
                                      <p>Status: {{ $pallet->status }}</p>
                                  @endforeach
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              </div>
                          </div>
                      </div>
                    </div>

                    {{-- Modal Update --}}
                    <div class="modal fade" id="modal-update{{ $data->id }}" tabindex="-1" aria-labelledby="modal-update{{ $data->id }}-label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title" id="modal-update{{ $data->id }}-label">Edit Pallet</h4>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/pallet/update/'.$data->id) }}" method="POST">
                              @csrf
                              @method('patch')
                              <div class="modal-body">
                                <input type="text" value="{{$data->id}}" hidden>
                                <div class="form-group mb-3">
                                  <input value="{{$data->no_delivery}}" type="text" class="form-control" id="no_delivery" name="no_delivery" placeholder="Enter No. Delivery" required>
                                </div>
                                <div class="form-group mb-3">
                                  <input value="{{$data->no_pallet}}" type="text" class="form-control" id="no_pallet" name="no_pallet" placeholder="Enter No. Delivery" required>
                                </div>
                                <div class="form-group mb-3">
                                    <select name="type_pallet" id="type_pallet" class="form-control">
                                        <option value="{{$data->type_pallet}}">{{$data->type_pallet}}</option>
                                        @foreach ($typePallet as $type)
                                            <option value="{{ $type->name_value }}">{{ $type->name_value }}</option>
                                        @endforeach
                                      </select>
                                </div>
                                  <div class="form-group mb-3">
                                    <input value="{{$data->date}}" type="date" class="form-control" id="date" name="date" placeholder="Enter Dropdown Category" required>
                                  </div>
                                  <div class="form-group mb-3">
                                    <select name="destination" id="destination" class="form-control">
                                        <option value="{{$data->destination}}">{{$data->destination}}</option>
                                        @foreach ($destinationPallet as $des)
                                            <option value="{{ $des->name_value }}">{{ $des->name_value }}</option>
                                        @endforeach
                                      </select>
                                </div>
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    {{-- Modal Update --}}

                    {{-- Modal Delete --}}
                    <div class="modal fade" id="modal-delete{{ $data->id }}" tabindex="-1" aria-labelledby="modal-delete{{ $data->id }}-label" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete Dropdown</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/pallet/delete/'.$data->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <div class="modal-body">
                                <div class="form-group">
                                Are you sure you want to delete <label for="Dropdown">{{ $data->no_pallet }}</label>?
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    {{-- Modal Delete --}}

                    {{-- Modal Access --}}
                    <div class="modal fade" id="modal-access}">
                      <div class="modal-dialog">
                          <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title">Give User Access</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <form action="{{ url('') }}" enctype="multipart/form-data" method="GET">
                          @csrf
                          <div class="modal-body">
                            
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-dark btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Submit">
                          </div>
                          </form>
                          </div>
                          <!-- /.modal-content -->
                      </div>
                    <!-- /.modal-dialog -->
                    </div>
                    {{-- Modal Revoke --}}

                    @endforeach
                  </tbody>
                </table>
              </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>

     
</main>
<!-- For Datatables -->


@endsection