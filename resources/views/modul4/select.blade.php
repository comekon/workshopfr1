@extends('layouts.app')

@section('title', 'Modul 4 - Select')

@push('style-page')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <!-- Basic Select Card -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card border border-dark">
            <div class="card-header bg-dark text-white font-weight-bold">
                Select
            </div>
            <div class="card-body">
                <form id="formAddKota1" novalidate>
                    <div class="form-group row align-items-center mb-3">
                        <label class="col-sm-3 col-form-label">Kota:</label>
                        <div class="col-sm-9">
                            <input type="text" id="addKota1" class="form-control border-primary" required>
                        </div>
                    </div>
                </form>
                <div class="text-end text-right mt-2 mb-4">
                    <button type="button" id="btnAddKota1" class="btn btn-success" onclick="tambahKota(1)">Tambahkan</button>
                </div>
                
                <div class="form-group d-flex align-items-center mb-4 bg-primary p-3 text-white">
                    <label class="mb-0 me-3 mr-3" style="min-width: 100px;">Select Kota:</label>
                    <select id="selectKota1" class="form-control" style="max-width:300px" onchange="updateTerpilih(1)">
                        <option value="">Pilih</option>
                    </select>
                </div>

                <div class="form-group row align-items-center mb-0 mt-4">
                    <label class="col-sm-3 col-form-label">Kota Terpilih <span class="text-danger"></span></label>
                    <div class="col-sm-9">
                        <input type="text" id="kotaTerpilih1" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Select2 Card -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card border border-dark">
            <div class="card-header bg-dark text-white font-weight-bold">
                select 2
            </div>
            <div class="card-body">
                <form id="formAddKota2" novalidate>
                    <div class="form-group row align-items-center mb-3">
                        <label class="col-sm-3 col-form-label">Kota:</label>
                        <div class="col-sm-9">
                            <input type="text" id="addKota2" class="form-control border-primary" required>
                        </div>
                    </div>
                </form>
                <div class="text-end text-right mt-2 mb-4">
                    <button type="button" id="btnAddKota2" class="btn btn-success" onclick="tambahKota(2)">Tambahkan</button>
                </div>
                
                <div class="form-group d-flex align-items-center mb-4 bg-primary p-3 text-white">
                    <label class="mb-0 me-3 mr-3" style="min-width: 100px;">Select Kota:</label>
                    <select id="selectKota2" class="form-control" style="max-width:300px; width: 100%;" onchange="updateTerpilih(2)">
                        <option value="">Pilih</option>
                    </select>
                </div>

                <div class="form-group row align-items-center mb-0 mt-4">
                    <label class="col-sm-3 col-form-label">Kota Terpilih <span class="text-danger"></span></label>
                    <div class="col-sm-9">
                        <input type="text" id="kotaTerpilih2" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 on the second select with theme
        $('#selectKota2').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih'
        });
        
        // Ensure onchange is explicitly bound for select2
        $('#selectKota2').on('change', function() {
            updateTerpilih(2);
        });
    });

    function tambahKota(cardId) {
        const form = document.getElementById('formAddKota' + cardId);
        const inputKota = document.getElementById('addKota' + cardId);
        const btn = document.getElementById('btnAddKota' + cardId);
        
        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const kotaName = inputKota.value;

        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        btn.disabled = true;

        setTimeout(() => {
            // Add option
            const selectTarget = document.getElementById('selectKota' + cardId);
            const newOption = document.createElement('option');
            newOption.value = kotaName;
            newOption.text = kotaName;
            
            selectTarget.appendChild(newOption);
            
            // if select2, we must trigger update
            if(cardId === 2) {
                $('#selectKota2').trigger('change.select2');
            }

            form.reset();
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000);
    }

    function updateTerpilih(cardId) {
        const val = document.getElementById('selectKota' + cardId).value;
        const inputTerpilih = document.getElementById('kotaTerpilih' + cardId);
        inputTerpilih.value = val;
    }
</script>
@endpush
