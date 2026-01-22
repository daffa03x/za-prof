<!-- Modal -->
<div class="modal fade" id="filterEvent" tabindex="-1" aria-labelledby="filterEventLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="filterEventLabel">Filter Event</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('event.filter') }}" method="GET">
            @csrf
            @method('GET')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                    <label for="mitra">Mitra</label>
                    <input type="text" class="form-control" id="mitra" name="mitra">
                    </div>
                    <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control" aria-label="Default select example">
                    <option disabled selected>Pilih Status</option>
                    <option value="0">Aktif</option>
                    <option value="1">Tidak Aktif</option>
                    </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>