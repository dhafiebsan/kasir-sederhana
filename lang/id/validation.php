<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa Validasi
    |--------------------------------------------------------------------------
    */

    'accepted'             => ':attribute harus diterima.',
    'accepted_if'          => ':attribute harus diterima ketika :other adalah :value.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'before'               => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'file'    => ':attribute harus berukuran antara :min sampai :max kilobita.',
        'string'  => ':attribute harus berisi antara :min sampai :max karakter.',
        'array'   => ':attribute harus memiliki antara :min sampai :max anggota.',
    ],
    'boolean'              => ':attribute harus bernilai benar atau salah.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'current_password'     => 'Kata sandi salah.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus terdiri dari :digits angka.',
    'digits_between'       => ':attribute harus terdiri dari :min sampai :max angka.',
    'email'                => 'Format :attribute tidak valid.',
    'ends_with'            => ':attribute harus diakhiri salah satu dari berikut: :values.',
    'exists'               => ':attribute yang dipilih tidak valid atau tidak ditemukan.',
    'file'                 => ':attribute harus berupa sebuah berkas.',
    'filled'               => ':attribute harus memiliki nilai.',
    'gt'                   => [
        'numeric' => ':attribute harus bernilai lebih besar dari :value.',
        'file'    => ':attribute harus berukuran lebih besar dari :value kilobita.',
        'string'  => ':attribute harus berisi lebih dari :value karakter.',
        'array'   => ':attribute harus memiliki lebih dari :value anggota.',
    ],
    'gte'                  => [
        'numeric' => ':attribute harus bernilai lebih besar dari atau sama dengan :value.',
        'file'    => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
        'string'  => ':attribute harus berisi lebih dari atau sama dengan :value karakter.',
        'array'   => ':attribute harus memiliki :value anggota atau lebih.',
    ],
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => ':attribute tidak ada di dalam :other.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa JSON string yang valid.',
    'lt'                   => [
        'numeric' => ':attribute harus bernilai lebih kecil dari :value.',
        'file'    => ':attribute harus berukuran lebih kecil dari :value kilobita.',
        'string'  => ':attribute harus berisi kurang dari :value karakter.',
        'array'   => ':attribute harus memiliki kurang dari :value anggota.',
    ],
    'lte'                  => [
        'numeric' => ':attribute harus bernilai lebih kecil dari atau sama dengan :value.',
        'file'    => ':attribute harus berukuran lebih kecil dari atau sama dengan :value kilobita.',
        'string'  => ':attribute harus berisi kurang dari atau sama dengan :value karakter.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :value anggota.',
    ],
    'max'                  => [
        'numeric' => ':attribute maksimal bernilai :max.',
        'file'    => ':attribute maksimal berukuran :max kilobita.',
        'string'  => ':attribute maksimal :max karakter.',
        'array'   => ':attribute maksimal memiliki :max anggota.',
    ],
    'mimes'                => ':attribute harus berupa berkas berformat: :values.',
    'mimetypes'            => ':attribute harus berupa berkas berformat: :values.',
    'min'                  => [
        'numeric' => ':attribute minimal bernilai :min.',
        'file'    => ':attribute minimal berukuran :min kilobita.',
        'string'  => ':attribute minimal berisi :min karakter.',
        'array'   => ':attribute minimal memiliki :min anggota.',
    ],
    'multiple_of'          => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => [
        'letters'       => ':attribute harus berisi setidaknya satu huruf.',
        'mixed'         => ':attribute harus berisi setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus berisi setidaknya satu angka.',
        'symbols'       => ':attribute harus berisi setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present'              => ':attribute wajib ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_if'          => ':attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => ':attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'        => ':attribute wajib diisi bila terdapat :values.',
    'required_with_all'    => ':attribute wajib diisi bila terdapat :values.',
    'required_without'     => ':attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => ':attribute wajib diisi bila sama sekali tidak terdapat :values.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => ':attribute harus berukuran :size.',
        'file'    => ':attribute harus berukuran :size kilobita.',
        'string'  => ':attribute harus berisi :size karakter.',
        'array'   => ':attribute harus mengandung :size anggota.',
    ],
    'starts_with'          => ':attribute harus diawali salah satu dari berikut: :values.',
    'string'               => ':attribute harus berupa teks.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah digunakan.',
    'uploaded'             => ':attribute gagal diunggah.',
    'url'                  => 'Format :attribute tidak valid.',
    'uuid'                 => ':attribute harus merupakan UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Kustomisasi Nama Atribut (Field)
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'email'         => 'email',
        'password'      => 'password',
        'nama_kategori' => 'nama kategori',
        'nama_produk'   => 'nama produk',
        'kategori_id'   => 'kategori',
        'harga'         => 'harga',
        'stok'          => 'stok',
        'satuan'        => 'satuan',
        'jumlah'        => 'jumlah beli',
        'bayar'         => 'uang bayar',
        'produk_id'     => 'produk',
        'tgl_mulai'     => 'tanggal mulai',
        'tgl_selesai'   => 'tanggal selesai',
    ],

];

