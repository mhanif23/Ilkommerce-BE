<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut ini berisi standar pesan kesalahan yang digunakan oleh
    | kelas validasi. Beberapa aturan mempunyai banyak versi seperti aturan 'size'.
    | Jangan ragu untuk mengoptimalkan setiap pesan yang ada di sini.
    |
    */

    'accepted' => 'Isian :attribute harus diterima.',
    'accepted_if' => 'Isian :attribute harus diterima ketika :other adalah :value.',
    'active_url' => 'Isian :attribute bukan URL yang valid.',
    'after' => 'Isian :attribute harus berisi tanggal setelah :date.',
    'after_or_equal' => 'Isian :attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha' => 'Isian :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Isian :attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num' => 'Isian :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Isian :attribute harus berisi sebuah array.',
    'before' => 'Isian :attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => 'Isian :attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => 'Isian :attribute harus bernilai antara :min sampai :max.',
        'file' => 'Isian :attribute harus berukuran antara :min sampai :max kilobita.',
        'string' => 'Isian :attribute harus berisi antara :min sampai :max karakter.',
        'array' => 'Isian :attribute harus memiliki :min sampai :max anggota.',
    ],
    'boolean' => 'Isian :attribute harus bernilai true atau false',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi salah.',
    'date' => 'Isian :attribute bukan tanggal yang valid.',
    'date_equals' => 'Isian :attribute harus berisi tanggal yang sama dengan :date.',
    'date_format' => 'Isian :attribute tidak cocok dengan format :format.',
    'declined' => 'Isian :attribute harus ditolak.',
    'declined_if' => 'Isian :attribute harus ditolak ketika :other adalah :value.',
    'different' => 'Isian :attribute dan :other harus berbeda.',
    'digits' => 'Isian :attribute harus :digits digit.',
    'digits_between' => 'Isian :attribute harus :min sampai :max digit.',
    'dimensions' => 'Isian :attribute harus merupakan dimensi gambar yang sah.',
    'distinct' => 'Isian :attribute memiliki nilai yang duplikat.',
    'email' => 'Isian :attribute harus berupa alamat surel yang valid.',
    'ends_with' => 'Isian :attribute harus diakhiri dengan: :values.',
    'enum' => 'Isian :attribute yang dipilih tidak valid.',
    'exists' => 'Isian :attribute yang dipilih tidak valid.',
    'file' => 'Isian :attribute harus berupa file.',
    'filled' => 'Isian :attribute wajib diisi.',
    'gt' => [
        'numeric' => 'Isian :attribute harus lebih besar dari :value.',
        'file' => 'Isian :attribute harus lebih besar dari :value kilobytes.',
        'string' => 'Isian :attribute harus lebih besar dari :value karakter.',
        'array' => 'Isian :attribute harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric' => 'Isian :attribute harus lebih besar dari atau sama dengan :value.',
        'file' => 'Isian :attribute harus lebih besar dari atau sama dengan :value kilobytes.',
        'string' => 'Isian :attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array' => 'Isian :attribute harus memiliki :value item atau lebih.',
    ],
    'image' => 'Isian :attribute harus berupa gambar.',
    'in' => 'Isian :attribute yang dipilih tidak valid.',
    'in_array' => 'Isian :attribute tidak terdapat dalam :other.',
    'integer' => 'Isian :attribute harus merupakan bilangan bulat.',
    'ip' => 'Isian :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Isian :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Isian :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Isian :attribute harus berupa JSON string yang valid.',
    'lt' => [
        'numeric' => 'Isian :attribute harus kurang dari :value.',
        'file' => 'Isian :attribute harus kurang dari :value kilobytes.',
        'string' => 'Isian :attribute harus kurang dari :value karakter.',
        'array' => 'Isian :attribute harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => 'Isian :attribute harus kurang dari atau sama dengan :value.',
        'file' => 'Isian :attribute harus kurang dari atau sama dengan :value kilobytes.',
        'string' => 'Isian :attribute harus kurang dari atau sama dengan :value karakter.',
        'array' => 'Isian :attribute tidak boleh lebih dari :value item.',
    ],
    'mac_address' => 'Isian :attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'numeric' => 'Isian :attribute seharusnya tidak lebih dari :max.',
        'file' => 'Isian :attribute seharusnya tidak lebih dari :max kilobytes.',
        'string' => 'Isian :attribute seharusnya tidak lebih dari :max karakter.',
        'array' => 'Isian :attribute seharusnya tidak lebih dari :max item.',
    ],
    'mimes' => 'Isian :attribute harus dokumen bertipe: :values.',
    'mimetypes' => 'Isian :attribute harus berupa file bertipe: :values.',
    'min' => [
        'numeric' => 'Isian :attribute harus minimal :min.',
        'file' => 'Isian :attribute harus minimal :min kilobytes.',
        'string' => 'Isian :attribute harus diisi :min karakter.',
        'array' => 'Isian :attribute harus minimal :min item.',
    ],
    'multiple_of' => 'Isian :attribute harus kelipatan dari :value.',
    'not_in' => 'Isian :attribute yang dipilih tidak valid.',
    'not_regex' => 'Isian :attribute format tidak valid.',
    'numeric' => 'Isian :attribute harus berupa angka.',
    'password' => 'Kata sandi tidak benar',
    'present' => 'Isian :attribute wajib ada.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'Format isian :attribute tidak valid.',
    'required' => 'Isian :attribute wajib diisi.',
    'required_array_keys' => 'Isian :attribute harus berisi entri untuk: :nilai.',
    'required_if' => 'Isian :attribute wajib diisi bila :other adalah :value.',
    'required_unless' => 'Isian :attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with' => 'Isian :attribute wajib diisi bila terdapat :values.',
    'required_with_all' => 'Isian :attribute wajib diisi bila terdapat :values.',
    'required_without' => 'Isian :attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => 'Isian :attribute wajib diisi bila tidak terdapat ada :values.',
    'same' => 'Isian :attribute dan :other harus sama.',
    'size' => [
        'numeric' => 'Isian :attribute harus berukuran :size.',
        'file' => 'Isian :attribute harus berukuran :size kilobyte.',
        'string' => 'Isian :attribute harus berukuran :size karakter.',
        'array' => 'Isian :attribute harus mengandung :size item.',
    ],
    'starts_with' => 'Isian :attribute harus dimulai dengan: :values.',
    'string' => 'Isian :attribute harus berupa string.',
    'timezone' => 'Isian :attribute harus berupa zona waktu yang valid.',
    'unique' => 'Isian :attribute sudah ada sebelumnya.',
    'uploaded' => 'Isian :attribute gagal mengunggah.',
    'url' => 'Format isian :attribute tidak valid.',
    'uuid' => 'Isian :attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
