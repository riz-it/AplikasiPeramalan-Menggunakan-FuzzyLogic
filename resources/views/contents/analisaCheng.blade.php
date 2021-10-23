@extends('template')
@section('content')
    <h1 class="h3 mb-4 text-gray-800 fade-in"></i>Analisa : Cheng</h1>
    <div class="row">

        <div class="bounce-top-icons col-lg-4">
            <!-- Basic Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hasil Analisa Mappe
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Hasil Mappe :</th>
                                <th style="text-align: right;">
                                    <?php if((round($mappe * 100)) < 10 && round($mappe * 100) > 0) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-success text-dark">{{round($mappe *100)}} %</span>
                                    <?php endif; ?>
                                    <?php if(round($mappe * 100) >= 10 && round($mappe * 100) < 20) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-info text-dark">{{round($mappe *100)}} %</span>
                                    <?php endif; ?>
                                    <?php if(round($mappe * 100) >= 20 && round($mappe * 100) < 50) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-warning text-dark">{{round($mappe *100)}} %</span>
                                    <?php endif; ?>
                                    <?php if(round($mappe * 100) >=  50) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-danger text-dark">{{round($mappe *100)}} %</span>
                                    <?php endif; ?>
                                    <?php if(round($mappe * 100) <  0) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-danger text-dark">Tidak dapat ditentukan</span>
                                    <?php endif; ?>
                                </th>
                            </tr>
                            <tr>
                                <th scope="col">Keterangan :</th>
                                <th style="text-align: right;">
                                    <?php if((round($mappe * 100)) < 10 && round($mappe * 100) > 0) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-success text-dark">Sangat Akurat</span>
                                    <?php endif; ?>
                                    <?php if(round($mappe * 100) >= 10 && round($mappe * 100) < 20) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-info text-dark">Cukup Akurat</span>
                                    <?php endif; ?>
                                    <?php if(round($mappe * 100) >= 20 && round($mappe * 100) < 50) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-warning text-dark">Kurang Akurat</span>
                                    <?php endif; ?>
                                    <?php if(round($mappe * 100) >=  50) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-danger text-dark">Sangat Kurang Akurat</span>
                                    <?php endif; ?>
                                    <?php if(round($mappe * 100) <  0) : ?>
                                    <span class="badge even-larger-badge rounded-pill alert-danger text-dark">Tidak dapat ditentukan</span>
                                    <?php endif; ?>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="slide-in-bottom col-lg-8">

            <!-- Collapsable Card Example -->
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse" role="button"
                    aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary">Hasil Analisa Prediksi</h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="collapseCardExample">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Prediksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item[1] }}</td>
                                            <td>@currency(round($item[4]))</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <script type="text/javascript">
        $(function() {
            $("#example1").dataTable({
                "ordering": false,
                "lengthMenu": [12, 24, 36, 48],
            });
        });

    </script>
@endsection
