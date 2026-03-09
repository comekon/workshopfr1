@extends('layouts.app')

@section('title', 'Modul 4 - DataTables')

@push('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Barang (DataTables)</h4>
                <form id="formAdd" novalidate>
                    <div class="form-group row align-items-center mb-3">
                        <label class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="addNama" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row align-items-center mb-3">
                        <label class="col-sm-3 col-form-label">Harga barang: <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" id="addHarga" class="form-control" required>
                        </div>
                    </div>
                </form>
                <div class="text-end text-right mt-3">
                    <button type="button" id="btnAdd" class="btn btn-success" onclick="submitAdd()">submit</button>
                </div>

                <hr class="mt-4 mb-4">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data from JS will be appended here -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Edit/Hapus -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit / Hapus Data</h5>
        <button type="button" class="close btn btn-sm btn-light" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalEdit').modal('hide')">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form id="formEdit" novalidate>
              <div class="form-group row mb-3">
                  <label class="col-sm-3 col-form-label">ID barang :</label>
                  <div class="col-sm-9">
                      <input type="text" id="editId" class="form-control" readonly>
                  </div>
              </div>
              <div class="form-group row mb-3">
                  <label class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                  <div class="col-sm-9">
                      <input type="text" id="editNama" class="form-control" required>
                  </div>
              </div>
              <div class="form-group row mb-3">
                  <label class="col-sm-3 col-form-label">Harga barang: <span class="text-danger">*</span></label>
                  <div class="col-sm-9">
                      <input type="number" id="editHarga" class="form-control" required>
                  </div>
              </div>
          </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" id="btnDelete" onclick="deleteRow()">Hapus</button>
        <button type="button" class="btn btn-success" id="btnUpdate" onclick="updateRow()">Ubah</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js-page')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    let currentId = 1;
    let currentRow = null;
    let dtTable;

    $(document).ready(function() {
        dtTable = $('#dataTable').DataTable();

        $('#dataTable tbody').on('click', 'tr', function () {
            if ($(this).find('.dataTables_empty').length > 0) return; // avoid empty row click
            
            currentRow = dtTable.row(this);
            let data = currentRow.data();
            if(data) {
                $('#editId').val(data[0]);
                $('#editNama').val(data[1]);
                $('#editHarga').val(data[2]);
                $('#modalEdit').modal('show');
            }
        });
    });

    function submitAdd() {
        const form = document.getElementById('formAdd');
        const btn = document.getElementById('btnAdd');
        
        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        btn.disabled = true;

        setTimeout(() => {
            const id = 'BRG-' + currentId++;
            const nama = $('#addNama').val();
            const harga = $('#addHarga').val();
            
            let rowNode = dtTable.row.add([id, nama, harga]).draw(false).node();
            $(rowNode).css('cursor', 'pointer');
            
            form.reset();
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000); 
    }

    function updateRow() {
        const form = document.getElementById('formEdit');
        const btn = document.getElementById('btnUpdate');
        
        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        btn.disabled = true;

        setTimeout(() => {
            if(currentRow) {
                currentRow.data([
                    $('#editId').val(), 
                    $('#editNama').val(), 
                    $('#editHarga').val()
                ]).draw(false);
                let rowNode = currentRow.node();
                $(rowNode).css('cursor', 'pointer');
            }
            $('#modalEdit').modal('hide');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000);
    }

    function deleteRow() {
        const btn = document.getElementById('btnDelete');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        btn.disabled = true;

        setTimeout(() => {
            if(currentRow) {
                currentRow.remove().draw(false);
                currentRow = null;
            }
            $('#modalEdit').modal('hide');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000);
    }
</script>
@endpush
