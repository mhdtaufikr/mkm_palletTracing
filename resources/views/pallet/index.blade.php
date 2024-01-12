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
                          <option value="">Search By</option>  
                          <option value="date">Date</option>
                            <option value="no_pallet">No Pallet</option>
                            <option value="storage">Storage</option>
                        </select>
                
                        <!-- No Pallet Input -->
                        <input name="palletNo" type="text" class="form-control" id="searchNoPallet" placeholder="Enter search term" style="display: none;">
                
                        <!-- Date Inputs -->
                        <input name="dateFrom" type="date" class="form-control" id="startDate" placeholder="Start Date" style="display: none;">
                        <input name="dateTo" type="date" class="form-control" id="endDate" placeholder="End Date" style="display: none;">
                
                        <!-- Storage Input -->
                        <select class="form-control" name="storage" id="searchStorage" style="display: none;">
                            @foreach ($destinationPallet as $storage)
                                <option value="{{ $storage->name_value }}">{{ $storage->name_value }}</option>
                            @endforeach
                        </select>
                
                        <button class="btn btn-dark btn-sm" type="submit" onclick="search()">Search</button>
                    </div>
                </form>
                
                   <script>
                    function toggleSearchInputs() {
                        var searchBy = document.getElementById('searchBy').value;
                        var searchNoPallet = document.getElementById('searchNoPallet');
                        var startDate = document.getElementById('startDate');
                        var endDate = document.getElementById('endDate');
                        var searchStorage = document.getElementById('searchStorage');

                        if (searchBy === 'no_pallet') {
                            searchNoPallet.style.display = 'block';
                            startDate.style.display = 'none';
                            endDate.style.display = 'none';
                            searchStorage.style.display = 'none';
                        } else if (searchBy === 'date') {
                            searchNoPallet.style.display = 'none';
                            startDate.style.display = 'inline-block';
                            endDate.style.display = 'inline-block';
                            searchStorage.style.display = 'none';
                        } else if (searchBy === 'storage') {
                            searchNoPallet.style.display = 'none';
                            startDate.style.display = 'none';
                            endDate.style.display = 'none';
                            searchStorage.style.display = 'block';
                        } else {
                            searchNoPallet.style.display = 'none';
                            startDate.style.display = 'none';
                            endDate.style.display = 'none';
                            searchStorage.style.display = 'none';
                        }
                    }

                    function search() {
                        var searchBy = document.getElementById('searchBy').value;
                        var searchTerm;

                        if (searchBy === 'no_pallet') {
                            searchTerm = document.getElementById('searchNoPallet').value;
                        } else if (searchBy === 'date') {
                            var startDate = document.getElementById('startDate').value;
                            var endDate = document.getElementById('endDate').value;
                            // Implement logic to use startDate and endDate for date search
                        } else if (searchBy === 'storage') {
                            searchTerm = document.getElementById('searchStorage').value;
                        }

                        // Perform search operation using searchTerm
                        console.log('Search By:', searchBy, 'Search Term:', searchTerm);
                    }
                </script>

                
              </div>
            </div>
              <div class="row">
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
                    @if(in_array(\Auth::user()->role, ['Super Admin', 'IT']))
                    <button type="button" class="btn btn-dark btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-add">
                      <i class="fas fa-plus-square"></i>
                  </button>
                  <button title="Import Pallet" type="button" class="btn btn-info btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-import">
                      Import Pallet
                  </button>
                    @endif
                      <!-- Modal -->
                      <div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-add-label">Add Pallet</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="addPalletForm" action="{{ url('/pallet/store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="no_delivery" name="no_delivery" placeholder="Enter No. Delivery" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <select name="destination" id="destination" class="form-control">
                                                <option value="">- Please Select Destination -</option>
                                                @foreach ($destinationPallet as $des)
                                                    <option value="{{ $des->name_value }}">{{ $des->name_value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                          <!-- Dynamic pallet dropdown will be added here -->
                                          <div class="input-group mb-3" id="noPalletContainer">
                                              <select name="no_pallet[]" class="form-control" required id="palletDropdown">
                                                  <option value="">- Please Select Pallet -</option>
                                                  <!-- Dynamic options will be added here -->
                                              </select>
                                              <button type="button" class="btn btn-success" onclick="addNoPalletField()" id="addButton" disabled>+</button>
                                          </div>
                                      </div>

                                        <div class="form-group mb-3">
                                            <input type="date" class="form-control" id="date" name="date" placeholder="Enter Date" required>
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
                        // Reference to the main dropdown
                        var mainPalletDropdown = document.querySelector('#noPalletContainer select');
                        var palletDropdown = document.getElementById('palletDropdown');
                        var addButton = document.getElementById('addButton');

                        // Add event listener to the dropdown
                          palletDropdown.addEventListener('change', function () {
                              // Enable/disable the button based on whether a value is selected
                              addButton.disabled = palletDropdown.value === '';
                          });
                        document.getElementById('destination').addEventListener('change', function () {
                            var destination = this.value;
                            if (destination) {
                                // Make an Ajax request to fetch no_pallet values for the selected destination
                                fetch('/getNoPallets/' + destination)
                                    .then(response => response.json())
                                    .then(data => updatePalletDropdown(data));

                                // Fetch all no_pallet values (for main pallet dropdown)
                                fetch('/getAllNoPallets/' + destination)
                                    .then(response => response.json())
                                    .then(data => updateMainPalletDropdown(data));
                            } else {
                                // Clear the pallet dropdown if no destination is selected
                                updatePalletDropdown([]);
                            }
                        });

                        function addNoPalletField() {
                            var container = document.getElementById('noPalletContainer');

                            // Save the values of existing dropdowns
                            var existingDropdowns = container.querySelectorAll('select');
                            var existingValues = Array.from(existingDropdowns).map(function (dropdown) {
                                return dropdown.value;
                            });
                            
                            var inputGroup = document.createElement('div');
                            inputGroup.className = 'input-group mt-3';

                            var select = document.createElement('select');
                            select.name = 'no_pallet[]';
                            select.className = 'form-control';
                            select.required = true;
                            select.innerHTML = '<option value="">- Please Select Pallet -</option>';
                            inputGroup.appendChild(select);

                            var button = document.createElement('button');
                            button.className = 'btn btn-danger';
                            button.type = 'button';
                            button.textContent = '-';
                            button.onclick = function () {
                                removeNoPalletField(button);
                            };
                            inputGroup.appendChild(button);

                            container.appendChild(inputGroup);

                            // Fetch no_pallet values for the selected destination
                            var destination = document.getElementById('destination').value;
                            if (destination) {
                                // Fetch no_pallet values for the selected destination
                                fetch('/getNoPallets/' + destination)
                                    .then(response => response.json())
                                    .then(data => updatePalletDropdown(data, select));
                            } else {
                                // Clear the new dropdown if no destination is selected
                                updatePalletDropdown([], select);
                            }

                            // Update the new dropdown with existing values
                            existingValues.forEach(function (value) {
                                var option = document.createElement('option');
                                option.value = value;
                                option.text = value;
                                select.appendChild(option);
                            });
                            addButton.disabled = false;
                        }

                        function updateMainPalletDropdown(mainPallets) {
                            // Clear previous options
                            mainPalletDropdown.innerHTML = '<option value="">- Please Select Pallet -</option>';

                            // Check if mainPallets is an array before iterating
                            if (Array.isArray(mainPallets)) {
                                // Add new options based on the fetched main_pallet values
                                mainPallets.forEach(function (mainPallet) {
                                    var option = document.createElement('option');
                                    option.value = mainPallet;
                                    option.text = mainPallet;
                                    mainPalletDropdown.appendChild(option);
                                });
                            } else {
                                console.error('Invalid data received for mainPallets:', mainPallets);
                            }
                        }

                        function updatePalletDropdown(noPallets, palletDropdown) {
                            // Clear previous options
                            palletDropdown.innerHTML = '<option value="">- Please Select Pallet -</option>';

                            // Check if noPallets is an array before iterating
                            if (Array.isArray(noPallets)) {
                                // Add new options based on the fetched no_pallet values
                                noPallets.forEach(function (pallet) {
                                    var option = document.createElement('option');
                                    option.value = pallet;
                                    option.text = pallet;
                                    palletDropdown.appendChild(option);
                                });
                            } else {
                                console.error('Invalid data received for noPallets:', noPallets);
                            }
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
                                      <h5 class="modal-title" id="modal-add-label">Import Pallet</h5>
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
                    <th>Storage</th>
                    <th>Date</th>
                    <th>Status</th>
                    @if(in_array(\Auth::user()->role, ['Super Admin', 'IT']))
                    <th>Action</th>
                    @endif
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
                                  Active
                              </button>
                          @else
                              <!-- Button for disposal status -->
                              <button class="btn btn-danger btn-sm">
                                  <i class="fa-solid fa-x"></i> Done
                              </button>
                          @endif
                        </td>
                        @if(in_array(\Auth::user()->role, ['Super Admin', 'IT']))
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
                        @endif
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