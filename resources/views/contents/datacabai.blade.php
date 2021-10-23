@extends('template')
@section('content')
    @if (Session::get('success_login'))
        <div class="container">
            <div class="alert alert-success">
                {{ Session::get('success_login') }}
            </div>
        </div>
    @endif
    
    <h1 class="h3 mb-4 text-gray-800 fade-in">Data</h1>
    <!-- Collapsable Card Example -->
    @if ($message = Session::get('success'))
        <div class="fade-in alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="row">

        <div class="col-lg-3">
            <div class="card bounce-top-icons shadow mb-4">
                <!-- Card Header - Accordion -->
                <a class="d-block px-3 py-3" data-toggle="collapse" role="button"
                    aria-expanded="true" >
                    <h6 class="m-0 font-weight-bold text-warning">Keterangan :</h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="asdda">
                   <div class="p-2">
                    <div class="alert alert-warning" role="alert">
                        Minimal data 12 bulan atau 1 tahun.
                      </div>
                   </div>
                </div>
            </div>
            <!-- Collapsable Card Example -->
            <div class="card bounce-top-icons shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse" role="button"
                    aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary">Input Data Cabai</h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="collapseCardExample">
                    <form action="{{ route('data.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Tanggal</label>
                                <input class="form-control" name="tanggal" type="date" id="tgl">
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput2" class="form-label">Harga Cabai</label>
                                <input type="text" autocomplete="off" class="form-control" name="harga" id="rupiah">
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <div class="btn-group justify-content-end" role="group" aria-label="Basic outlined example">
                                    <button type="submit" class="btn btn-outline-success"><i
                                            class="far fa-save"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card bounce-top-icons shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#collapsefile" class="d-block card-header py-3" data-toggle="collapse" role="button"
                    aria-expanded="true" aria-controls="collapsefile">
                    <h6 class="m-0 font-weight-bold text-primary">Input Data Cabai (Excel)</h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="collapsefile">
                    <div class="card-body">
                        <form action="{{ url('import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                            <hr>
                            <b>Format - <i>pastikan format kolom benar!</i></b>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tanggal (date)</th>
                                        <th scope="col">Harga (general)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= date('d/m/Y') ?></td>
                                                <td>50000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="submit" class="btn btn-success btn-circle">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="col-lg-9 slide-in-bottom">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data :</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Rata Rata</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->tanggal }}</td>
                                                <td>@currency($item->harga)</td>
                                                <td>
                                                    <form action="{{ route('data.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                                            <button onclick="functionedit({{ $item->id }})" type="button"
                                                                class="btn btn-outline-warning"><i class="far fa-edit"></i></button>

                                                            <button
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                                                type="submit" class="btn btn-outline-danger"><i
                                                                    class="far fa-trash-alt"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            @push('script')
                        <script>
                            $(function() {
                                $("#dataTable").dataTable({
                                    
                                });
                            });
                            function functionedit(id) {
                                $('#id').val(id);
                                $.ajax({
                                    type: "GET",
                                    url: "/data/" + id,
                                    success: function(response) {
                                        var tanggal = new Date(response.tanggal);
                                        var ad = tanggal.setDate(tanggal.getDate());
                                        document.getElementById('tgl').valueAsDate = new Date(ad);
                                        $('#rupiah').val(response.harga);
                                    }
                                });

                            }


                            var rupiah = document.getElementById('rupiah');
                            rupiah.addEventListener('keyup', function(e) {
                                // tambahkan 'Rp.' pada saat form di ketik
                                // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
                                rupiah.value = formatRupiah(this.value);
                            });

                            /* Fungsi formatRupiah */
                            function formatRupiah(angka, prefix) {
                                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                                    split = number_string.split(','),
                                    sisa = split[0].length % 3,
                                    rupiah = split[0].substr(0, sisa),
                                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                                if (ribuan) {
                                    separator = sisa ? '.' : '';
                                    rupiah += separator + ribuan.join('.');
                                }

                                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
                            }

                        </script>
            @endpush
@endsection
