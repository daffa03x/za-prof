<!-- Modal -->
<div class="modal fade" id="exportTransaksi" tabindex="-1" aria-labelledby="exportTransaksiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exportTransaksiLabel">Export Transaksi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" action="{{ route('transaksi.export') }}">
        @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="id_event">Id Event</label>
                    <input type="text" class="form-control" id="id_event" name="id_event">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="tanggal_awal">Tanggal Awal</label>
                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal">
                    </div>
                    <div class="form-group col-md-6">
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tanggal_akhir">Status Pembayaran</label>
                        <select id="inputState" name="status_pembayaran" class="form-control">
                            <option selected disabled>Choose...</option>
                            <option value="Success">Success</option>
                            <option value="Failed">Failed</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputState">Jenis Transaksi</label>
                        <select id="inputState" name="payment_id" class="form-control">
                            <option selected disabled value="">Choose...</option>
                            @foreach($payment as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>