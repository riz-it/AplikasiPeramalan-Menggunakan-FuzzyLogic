<?php

namespace App\Http\Controllers;

use App\Models\Cabai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Echo_;

use function Complex\log10;

class PagesController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function verified_login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $kredensil = $request->only('username', 'password');

        $userInfo = User::where('username', '=', $request->username)->first();

        if (!$userInfo) {
            return back()->with('fail', 'Username not found');
        } else {

            //check password
            if (Hash::check($request->password, $userInfo->password)) {
                if (Auth::attempt($kredensil)) {
                    $user = Auth::user();
                    return redirect()->intended('data')->with('success_login', 'Berhasil login');
                } else {
                    return redirect('/');
                }
            } else {
                return back()->with('fail', 'Password incorrect');
            }
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/')->with('success', 'Success logout');
    }

    public function analisaChen()
    {
        // Pengambilan data rata rata perbulan pertahun

        $data = Cabai::select(
            DB::raw('avg(harga) as harga'),
            DB::raw("DATE_FORMAT(tanggal,'%M %Y') as months")
        )
            ->groupBy('months')
            ->orderBy('tanggal', 'ASC')
            ->get();

        // Pengambilan data rata rata perbulan pertahun
        $BanyakDataTahun = Cabai::select(
            DB::raw("DATE_FORMAT(tanggal,'%Y') as years")
        )
            ->groupBy('years')
            ->get();

        // Pengambilan data rata rata terkecil
        $min = Cabai::select(
            DB::raw('avg(harga) as harga'),
            DB::raw("DATE_FORMAT(tanggal,'%M %Y') as months")
        )
            ->groupBy('months')
            ->orderBy('harga', 'ASC')
            ->first();

        // Pengambilan data rata rata terbesar
        $max = Cabai::select(
            DB::raw('avg(harga) as harga'),
            DB::raw("DATE_FORMAT(tanggal,'%M %Y') as months")
        )
            ->groupBy('months')
            ->orderBy('harga', 'DESC')
            ->first();

        // Menghitung banyak data perbulan pertahun
        $count = count($data);


        if ($count <= 1) {
            return view('contents.analisaChen', [
                'data' => $dataPrediksi = array(),
                'active' => 2,
                'activeTipe' => 1,
                'mappe' => $hasilMappe = -1,
            ]);
        }

        // Fungsi logaritma matematika dari jumlah data
        $log = log10($count);

        // Pembersihan data hasil logaritma
        $int = substr($log, 0, 5);

        // Penghitungan jumlah interval
        $jumlahinterval = 1 + 3.3 * $int;


        // Pengitungan lebar interval
        $lebarinterval = ($max['harga'] - $min['harga']) / round($jumlahinterval);

        // Membuat batas data
        $batasU = round($jumlahinterval);

        // Pembuatan array untuk menampung data
        $intervalA = array();
        $intervalB = array();

        // Penjabaran data dan disimpan ke variabel

        // Menampung data intervalB
        for ($i = 1; $i < $batasU + 1; $i++) {
            $intervalB[] = round($min['harga'] + ($lebarinterval * $i));
        }

        // Menampung data pertama(statis) intervalA
        $intervalA[] = round($min['harga']);

        // Menampung data lanjutan(dinamis) intervalA
        for ($i = 0; $i < $batasU - 1; $i++) {
            $intervalA[] = round($intervalB[$i]);
        }


        // Pembuatan variabel nilai tengah
        $A = array();

        // Pengisian variabel A
        for ($i = 0; $i < $batasU; $i++) {
            $A["A" . ($i + 1)] = ($intervalA[$i] + $intervalB[$i]) / 2;
        };


        // Pembuatan array fuzzyfikasi dan relasi
        $relasi = array();
        $arraydata = array();


        $PanjangIntervalA =  count($intervalA); // panjang interval 6
        // echo $PanjangIntervalA;

        // Perulangan untuk mendapatkan data fuzzyfikasi
        foreach ($data as $key) {
            $arraydata[1] = $key->months;
            $arraydata[2] = round($key->harga);

            for ($i = $PanjangIntervalA; $i > 0; $i--) {
                if (round($key->harga) > $intervalA[$i - 1]) {
                    $arraydata[3] = "A" . (($i - 1) + 1);
                    break;
                }
            }
            $relasi[] = $arraydata;
        }


        // setup array index ke 0
        $relasi[0][4] = "-";
        $relasi[0]["sebelum"] = "";
        $relasi[0]["setelah"] = "";
        $relasi[0][""] = "";

        // pembuatan variabel banyak data dikurangi index ke 0
        $countI = $count - 1;

        // perulangan untuk mendapatkan relasi antar data
        for ($i = $countI; $i > 0; $i--) {
            $relasi[$i][4] = $relasi[$i - 1][3] . "->" . $relasi[$i][3];
            $relasi[$i]["sebelum"] = $relasi[$i - 1][3];
            $relasi[$i]["setelah"] = $relasi[$i][3];
            $relasi[$i][$relasi[$i - 1][3]] = $relasi[$i][3];
            // $relasi[$i][$relasi[$i - 1][3]] =  $relasi[$i][3];
        }

        // Pembuatan variabel dan array untuk pembersihan data relasi
        $FLRGDataA = "";
        $FLRGData = array();
        $FLRG = array();
        $G = array();

        // perulangan untuk mencari relasi yang berhubungan dengan data
        for ($i = 0; $i < $PanjangIntervalA; $i++) {
            $FLRG[1] = "A" . ($i + 1);

            for ($x = 0; $x < count($data); $x++) {
                for ($y = 0; $y < $PanjangIntervalA; $y++) {
                    if ($relasi[$x]["sebelum"] == "A" . ($i + 1)) {
                        $FLRGDataA = $relasi[$x]['setelah'];
                        $FLRGData[] = $FLRGDataA;
                        break;
                    }
                }
            }
            $FLRG[2] = $FLRGData;
            $G[] = $FLRG;
        }


        // perhitungan data yang tertumpuk dengan data sebelumnya
        $dataPerulangan = array();
        for ($i = 0; $i < count($G); $i++) {
            $count = count($G[$i][2]);
            $dataPerulangan[] = $count;
        };

        // pemecahan data dan pembersihan data
        for ($x = 0; $x < (count($G) - 1); $x++) {
            for ($i = 0; $i < $dataPerulangan[$x]; $i++) {
                \array_splice($G[$x + 1][2], 0, 1);
            }
        }

        // pembersihan data relasi yang dama(duplikat)
        for ($i = 0; $i < count($G); $i++) {
            $duplicates = array_filter(array_count_values($G[$i][2]), function ($count_values) {
                // ^ import variable to the closure scope
                return $count_values > 1;
            });
            $G[$i][2] = array_values(array_unique($G[$i][2]));
        }



        $nilai = array();
        // $value = array();
        for ($i = 0; $i < count($G); $i++) {
            $jumlahrelasi = count($G[$i][2]);
            // $jumlah = count($G[$i]);
            for ($x = 1; $x < $jumlahrelasi + 1; $x++) {
                $key = $G[$i][2][$x - 1];

                $nilai[] = $A[$key];
            }
            $G[$i][3] = $nilai;
            $G[$i][4] = $jumlahrelasi;
        }


        $pemecah = array();
        for ($i = 0; $i < count($G); $i++) {
            $count = count($G[$i][3]);
            $pemecah[] = $count;
        };

        for ($x = 0; $x < (count($G) - 1); $x++) {
            for ($i = 0; $i < $pemecah[$x]; $i++) {
                \array_splice($G[$x + 1][3], 0, 1);
            }
        }

        for ($i = 0; $i < count($G); $i++) {
            $G[$i][5] = array_sum($G[$i][3]);
        }
        // dd($relasi);

        // dd($array[] = $duplicates);
        for ($i = 0; $i < count($G); $i++) {
            if ($G[$i][5] == 0) {
                $G[$i][6] = 0;
            } else {
                $G[$i][6] = $G[$i][5] / $G[$i][4];
            }
        }



        // Pembuatan variabel untuk menyimpan data prediksi
        $dataPrediksi = array();
        $arraydataPrediksi = array();


        $PanjangIntervalA =  count($intervalA); // panjang interval 6

        // Perulangan untuk mendapatkan data fuzzyfikasi prediksi
        foreach ($data as $key) {
            $arraydataPrediksi[1] = $key->months;
            $arraydataPrediksi[2] = round($key->harga);

            for ($i = $PanjangIntervalA; $i > 0; $i--) {
                if (round($key->harga) > $intervalA[$i - 1]) {
                    $arraydataPrediksi[3] = "A" . (($i - 1) + 1);
                    break;
                }
            }
            $dataPrediksi[] = $arraydataPrediksi;
        }


        // set index ke 0 data prediksi
        $dataPrediksi[0][4] = "0";
        $dataPrediksi[0][5] = "0";
        $dataPrediksi[0][6] = "0";
        $arrayTanggal = explode(" ", $dataPrediksi[0][1]);
        $dataPrediksi[0][7] = 1 . " " . $arrayTanggal[0] . " " . ($arrayTanggal[1] + count($BanyakDataTahun));


        // pengisian data prediksi dimulai dari data ke 1
        for ($i = 1; $i < count($dataPrediksi); $i++) {
            for ($x = 0; $x < count($G); $x++) {
                if ($dataPrediksi[$i][3] == $G[$x][1]) {
                    $dataPrediksi[$i][4] = $G[$x][6];
                    break;
                }
            }
            $dataPrediksi[$i][5] = abs(number_format(($dataPrediksi[$i][2] - $dataPrediksi[$i][4]) / $dataPrediksi[$i][2], 5));
            $dataPrediksi[$i][6] = $dataPrediksi[$i][5] * 100 . "%";
            $arrayTanggal = explode(" ", $dataPrediksi[$i][1]);
            $dataPrediksi[$i][7] = 1 . " " . $arrayTanggal[0] . " " . ($arrayTanggal[1] + count($BanyakDataTahun));
        }

        // menjabarkan data mappe
        $mappe = array();
        for ($i = 0; $i < count($dataPrediksi); $i++) {
            $mappe[] = $dataPrediksi[$i][5];
        }

        // menghitung data mappe
        $hasilMappe = array_sum($mappe) / count($dataPrediksi);


        // mengembalikan halaman
        return view('contents.analisaChen', [
            'data' => $dataPrediksi,
            'active' => 2,
            'activeTipe' => 1,
            'mappe' => $hasilMappe,
        ]);
    }

    public function analisaCheng()
    {
        // Pengambilan data rata rata perbulan pertahun
        $data = Cabai::select(
            DB::raw('avg(harga) as harga'),
            DB::raw("DATE_FORMAT(tanggal,'%M %Y') as months")
        )
            ->groupBy('months')
            ->orderBy('tanggal', 'ASC')
            ->get();

        // Pengambilan data rata rata perbulan pertahun
        $BanyakDataTahun = Cabai::select(
            DB::raw("DATE_FORMAT(tanggal,'%Y') as years")
        )
            ->groupBy('years')
            ->get();

        // Pengambilan data rata rata terkecil
        $min = Cabai::select(
            DB::raw('avg(harga) as harga'),
            DB::raw("DATE_FORMAT(tanggal,'%M %Y') as months")
        )
            ->groupBy('months')
            ->orderBy('harga', 'ASC')
            ->first();

        // Pengambilan data rata rata terbesar
        $max = Cabai::select(
            DB::raw('avg(harga) as harga'),
            DB::raw("DATE_FORMAT(tanggal,'%M %Y') as months")
        )
            ->groupBy('months')
            ->orderBy('harga', 'DESC')
            ->first();

        // Menghitung banyak data perbulan pertahun
        $count = count($data);
        if ($count <= 1) {
            return view('contents.analisaCheng', [
                'data' => $dataPrediksi = array(),
                'active' => 2,
                'activeTipe' => 1,
                'mappe' => $hasilMappe = -1,
            ]);
        }

        // Fungsi logaritma matematika dari jumlah data
        $log = log10($count);

        // Pembersihan data hasil logaritma
        $int = substr($log, 0, 5);

        // Penghitungan jumlah interval
        $jumlahinterval = 1 + 3.3 * $int;

        // Pengitungan lebar interval
        $lebarinterval = ($max['harga'] - $min['harga']) / round($jumlahinterval);

        // Membuat batas data
        $batasU = round($jumlahinterval);

        $lebarinterval2 = $lebarinterval / 2;

        // Pembuatan array untuk menampung data
        $intervalA = array();
        $intervalB = array();

        // Penjabaran data dan disimpan ke variabel

        // Menampung data intervalB
        for ($i = 1; $i < $batasU + 1; $i++) {
            $intervalB[] = round($min['harga'] + ($lebarinterval * $i));
        }

        // Menampung data pertama(statis) intervalA
        $intervalA[] = round($min['harga']);

        // Menampung data lanjutan(dinamis) intervalA
        for ($i = 0; $i < $batasU - 1; $i++) {
            $intervalA[] = round($intervalB[$i]);
        }

        // pembuatan frekuensi
        $frekuensiSementara = array();

        for ($i = 0; $i < $count; $i++) {
            for ($x = 0; $x < $batasU; $x++) {
                if ($data[$i]['harga'] >= $intervalA[$x] && $data[$i]['harga'] <= $intervalB[$x]) {
                    $frekuensiSementara[] = $x;
                    break;
                }
            }
        }

        // menghitung jumlah dalam data yang sama
        array_count_values($frekuensiSementara);
        $frekuensi = array();
        $frekuensi = array_values(array_count_values($frekuensiSementara));

        $A = array();

        // Pengisian variabel A
        for ($i = 0; $i < $batasU; $i++) {
            $A["A" . ($i + 1)] = ($intervalA[$i] + $intervalB[$i]) / 2;
        };

        // variabel untuk menampung data yang melebihi dan tidak melebihi batasan
        $above = array();
        $below = array();

        // pengisian data
        for ($i = 0; $i < count($frekuensi); $i++) {
            if ($frekuensi[$i] > $batasU) {
                $above[] = "above";
            } else {
                $below[] = "below";
            }
        }


        // Pembuatan array untuk menampung data
        $intervalC = array();
        $intervalD = array();

        if (empty($above)) {

            // Pembuatan nilai tengah
            $NilaiTengah = array();



            // Pembuatan array fuzzyfikasi dan relasi
            $relasi = array();
            $arraydata = array();


            $PanjangIntervalB =  count($intervalB); // panjang interval 9

            //  // Perulangan untuk mendapatkan data fuzzyfikasi
            foreach ($data as $key) {
                $arraydata[1] = $key->months;
                $arraydata[2] = round($key->harga);

                for ($i = $PanjangIntervalB; $i > 0; $i--) {
                    if (round($key->harga) > $intervalA[$i - 1]) {
                        $arraydata[3] = "A" . (($i - 1) + 1);
                        break;
                    }
                }
                $relasi[] = $arraydata;
            }


            // setup array index ke 0
            $relasi[0][4] = "-";
            $relasi[0]["sebelum"] = "";
            $relasi[0]["setelah"] = "";
            $relasi[0][""] = "";
            // pembuatan variabel banyak data dikurangi index ke 0
            $countI = $count - 1;

            // perulangan untuk mendapatkan relasi antar data
            for ($i = $countI; $i > 0; $i--) {
                $relasi[$i][4] = $relasi[$i - 1][3] . "->" . $relasi[$i][3];
                $relasi[$i]["sebelum"] = $relasi[$i - 1][3];
                $relasi[$i]["setelah"] = $relasi[$i][3];
                $relasi[$i][$relasi[$i - 1][3]] = $relasi[$i][3];
                // $relasi[$i][$relasi[$i - 1][3]] =  $relasi[$i][3];
            }


            // pembuatan batasan data
            $batasU2 = count($intervalB);
            $A2 = array();
            $A1 = array();
            $dataA2 = array();
            $basisData = array();

            for ($i = 0; $i < $count; $i++) {
                $basisData[] = $relasi[$i][4];
            }

            $dataA2 = array_count_values($basisData);

            for ($i = 1; $i < ($batasU2 + 1); $i++) {
                for ($x = 1; $x < ($batasU2 + 1); $x++) {
                    for ($y = 0; $y < count($relasi); $y++) {
                        if ($relasi[$y][4] == "A" . $i . "->" . "A" . $x) {
                            $A2["A" . $x] = $dataA2["A" . $i . "->" . "A" . $x];
                            break;
                        } else {
                            $A2["A" . $x] = 0;
                        }
                    }
                }
                $A1["A" . $i] = $A2;
            }
            for ($i = 1; $i < (count($A1) + 1); $i++) {
                $A1["A" . $i]["total"] = array_sum($A1["A" . $i]);
            }


            $B2 = array();
            $B1 = array();


            for ($x = 1; $x < ($batasU2 + 1); $x++) {
                for ($i = 1; $i < ($batasU2 + 1); $i++) {
                    $B2["A" . $i] = $A1["A" . $x]["A" . $i] / $A1["A" . $x]["total"];
                }
                $B1["A" . $x] = $B2;
            }
            // dd($B1);

            for ($i = 1; $i < (count($B1) + 1); $i++) {
                $B1["A" . $i]["nilai_tengah"] = $A["A" . $i];
            }

            $value = array();

            // melengkapi data yang dibutuhkan
            for ($i = 1; $i < (count($B1) + 1); $i++) {
                for ($x = 1; $x < (count($B1) + 1); $x++) {
                    $value["A" . $i][$x] = $B1["A" . $i]["A" . $x] * $B1["A" . $x]["nilai_tengah"];
                }
            }

            // menghitung total dari hasil data
            for ($i = 1; $i < (count($B1) + 1); $i++) {
                $value["A" . $i]["total"] = array_sum($value["A" . $i]);
            }
            // dd($value);
            // Pembuatan variabel untuk menyimpan data prediksi
            $dataPrediksi = array();
            $arraydataPrediksi = array();


            $PanjangIntervalDataPrediksi =  count($A); // panjang interval 6

            // Perulangan untuk mendapatkan data fuzzyfikasi prediksi
            foreach ($data as $key) {
                $arraydataPrediksi[1] = $key->months;
                $arraydataPrediksi[2] = round($key->harga);

                for ($i = $PanjangIntervalDataPrediksi; $i > 0; $i--) {
                    if (round($key->harga) > $intervalA[$i - 1]) {
                        $arraydataPrediksi[3] = "A" . (($i - 1) + 1);
                        break;
                    }
                }
                $dataPrediksi[] = $arraydataPrediksi;
            }


            // // set index ke 0 data prediksi
            $dataPrediksi[0][4] = "0";
            $dataPrediksi[0][5] = "0";
            $dataPrediksi[0][6] = "0";
            $arrayTanggal = explode(" ", $dataPrediksi[0][1]);
            $dataPrediksi[0][7] = 1 . " " . $arrayTanggal[0] . " " . ($arrayTanggal[1] + count($BanyakDataTahun));


            // pengisian data prediksi dimulai dari data ke 1

            for ($i = 1; $i < count($dataPrediksi); $i++) {
                for ($x = 1; $x < (count($A) + 1); $x++) {
                    if ($dataPrediksi[$i][3] == "A" . $x) {
                        $dataPrediksi[$i][4] = $A[$dataPrediksi[$i][3]];
                        // echo "asd";
                        break;
                    }
                }
                $dataPrediksi[$i][5] = abs(number_format(($dataPrediksi[$i][2] - $dataPrediksi[$i][4]) / $dataPrediksi[$i][2], 5));
                $dataPrediksi[$i][6] = round($dataPrediksi[$i][5] * 100) . "%";
                $arrayTanggal = explode(" ", $dataPrediksi[$i][1]);
                $dataPrediksi[$i][7] = 1 . " " . $arrayTanggal[0] . " " . ($arrayTanggal[1] + count($BanyakDataTahun));
            }

            // pembuatan data mappe
            $mappe = array();

            // penjabaran data mappe
            for ($i = 0; $i < count($dataPrediksi); $i++) {
                $mappe[] = $dataPrediksi[$i][5];
            }

            // menampung data mappe akhir
            $hasilMappe = array_sum($mappe) / count($dataPrediksi);

            return view('contents.analisaCheng', [
                'data' => $dataPrediksi,
                'active' => 2,
                'activeTipe' => 2,
                'mappe' => $hasilMappe,
            ]);
        }

        for ($i = 1; $i < ((count($above) * 2) + 1); $i++) {
            $intervalD[] = round($min['harga'] + ($lebarinterval2 * $i));
        }
        for ($i = 1; $i < (count($below) + 1); $i++) {
            $index = count($intervalD);
            $intervalD[$index] = $intervalD[$index - $i] + ($lebarinterval * $i);
        }



        // Menampung data pertama(statis) intervalD
        $intervalC[] = round($min['harga']);

        // Menampung data lanjutan(dinamis) intervalD
        for ($i = 0; $i < (count($intervalD) - 1); $i++) {
            $intervalC[] = round($intervalD[$i]);
        }

        // Pembuatan nilai tengah
        $NilaiTengah = array();

        // Pengisian variabel Nilai Tengah
        for ($i = 0; $i < count($intervalD); $i++) {
            $NilaiTengah["A" . ($i + 1)] = ($intervalC[$i] + $intervalD[$i]) / 2;
        };

        // Pembuatan array fuzzyfikasi dan relasi
        $relasi = array();
        $arraydata = array();


        $PanjangIntervalC =  count($intervalC); // panjang interval 9

        //  // Perulangan untuk mendapatkan data fuzzyfikasi
        foreach ($data as $key) {
            $arraydata[1] = $key->months;
            $arraydata[2] = round($key->harga);

            for ($i = $PanjangIntervalC; $i > 0; $i--) {
                if (round($key->harga) > $intervalC[$i - 1]) {
                    $arraydata[3] = "A" . (($i - 1) + 1);
                    break;
                }
            }
            $relasi[] = $arraydata;
        }

        // setup array index ke 0
        $relasi[0][4] = "-";
        $relasi[0]["sebelum"] = "";
        $relasi[0]["setelah"] = "";
        $relasi[0][""] = "";

        // pembuatan variabel banyak data dikurangi index ke 0
        $countI = $count - 1;

        // perulangan untuk mendapatkan relasi antar data
        for ($i = $countI; $i > 0; $i--) {
            $relasi[$i][4] = $relasi[$i - 1][3] . "->" . $relasi[$i][3];
            $relasi[$i]["sebelum"] = $relasi[$i - 1][3];
            $relasi[$i]["setelah"] = $relasi[$i][3];
            $relasi[$i][$relasi[$i - 1][3]] = $relasi[$i][3];
            // $relasi[$i][$relasi[$i - 1][3]] =  $relasi[$i][3];
        }

        // pembuatan batasan data
        $batasU2 = count($intervalD);
        $A2 = array();
        $A1 = array();
        $dataA2 = array();
        $basisData = array();

        for ($i = 0; $i < $count; $i++) {
            $basisData[] = $relasi[$i][4];
        }
        $dataA2 = array_count_values($basisData);

        for ($i = 1; $i < ($batasU2 + 1); $i++) {
            for ($x = 1; $x < ($batasU2 + 1); $x++) {
                for ($y = 0; $y < count($relasi); $y++) {
                    if ($relasi[$y][4] == "A" . $i . "->" . "A" . $x) {
                        $A2["A" . $x] = $dataA2["A" . $i . "->" . "A" . $x];
                        break;
                    } else {
                        $A2["A" . $x] = 0;
                    }
                }
            }
            $A1["A" . $i] = $A2;
        }
        for ($i = 1; $i < (count($A1) + 1); $i++) {
            $A1["A" . $i]["total"] = array_sum($A1["A" . $i]);
        }

        $B2 = array();
        $B1 = array();

        for ($x = 1; $x < ($batasU2 + 1); $x++) {
            for ($i = 1; $i < ($batasU2 + 1); $i++) {
                $B2["A" . $i] = $A1["A" . $x]["A" . $i] / $A1["A" . $x]["total"];
            }
            $B1["A" . $x] = $B2;
        }

        for ($i = 1; $i < (count($B1) + 1); $i++) {
            $B1["A" . $i]["nilai_tengah"] = $NilaiTengah["A" . $i];
        }

        $value = array();

        // melengkapi data yang dibutuhkan
        for ($i = 1; $i < (count($B1) + 1); $i++) {
            for ($x = 1; $x < (count($B1) + 1); $x++) {
                $value["A" . $i][$x] = $B1["A" . $i]["A" . $x] * $B1["A" . $x]["nilai_tengah"];
            }
        }

        // menghitung total dari hasil data
        for ($i = 1; $i < (count($B1) + 1); $i++) {
            $value["A" . $i]["total"] = array_sum($value["A" . $i]);
        }

        // Pembuatan variabel untuk menyimpan data prediksi
        $dataPrediksi = array();
        $arraydataPrediksi = array();


        $PanjangIntervalDataPrediksi =  count($intervalC); // panjang interval 6

        // Perulangan untuk mendapatkan data fuzzyfikasi prediksi
        foreach ($data as $key) {
            $arraydataPrediksi[1] = $key->months;
            $arraydataPrediksi[2] = round($key->harga);

            for ($i = $PanjangIntervalDataPrediksi; $i > 0; $i--) {
                if (round($key->harga) > $intervalC[$i - 1]) {
                    $arraydataPrediksi[3] = "A" . (($i - 1) + 1);
                    break;
                }
            }
            $dataPrediksi[] = $arraydataPrediksi;
        }


        // // set index ke 0 data prediksi
        $dataPrediksi[0][4] = "0";
        $dataPrediksi[0][5] = "0";
        $dataPrediksi[0][6] = "0";
        $arrayTanggal = explode(" ", $dataPrediksi[0][1]);
        $dataPrediksi[0][7] = 1 . " " . $arrayTanggal[0] . " " . ($arrayTanggal[1] + count($BanyakDataTahun));

        // dd($value);

        // pengisian data prediksi dimulai dari data ke 1
        for ($i = 1; $i < count($dataPrediksi); $i++) {
            for ($x = 1; $x < (count($value) + 1); $x++) {
                if ($dataPrediksi[$i][3] == "A" . $x) {
                    $dataPrediksi[$i][4] = $value["A" . $x]["total"];
                    break;
                }
            }
            $dataPrediksi[$i][5] = abs(number_format(($dataPrediksi[$i][2] - $dataPrediksi[$i][4]) / $dataPrediksi[$i][2], 5));
            $dataPrediksi[$i][6] = round($dataPrediksi[$i][5] * 100) . "%";
            $arrayTanggal = explode(" ", $dataPrediksi[$i][1]);
            $dataPrediksi[$i][7] = 1 . " " . $arrayTanggal[0] . " " . ($arrayTanggal[1] + count($BanyakDataTahun));
        }

        // pembuatan data mappe
        $mappe = array();

        // penjabaran data mappe
        for ($i = 0; $i < count($dataPrediksi); $i++) {
            $mappe[] = $dataPrediksi[$i][5];
        }

        // menampung data mappe akhir
        $hasilMappe = array_sum($mappe) / count($dataPrediksi);

        return view('contents.analisaCheng', [
            'data' => $dataPrediksi,
            'active' => 2,
            'activeTipe' => 2,
            'mappe' => $hasilMappe,
        ]);
    }
}
