<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Pembersihan Data Lama ──
        Schema::disableForeignKeyConstraints();
        Pembelian::truncate();
        Produk::truncate();
        Brand::truncate();
        Kategori::truncate();
        Schema::enableForeignKeyConstraints();

        // ── Admin User ──────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@tokosparepart.com'],
            [
                'name' => 'Admin Toko',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // ── Customer Users ──────────────────────────────────────
        $customer1 = User::updateOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]
        );

        $customer2 = User::updateOrCreate(
            ['email' => 'siti@example.com'],
            [
                'name' => 'Siti Rahayu',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]
        );

        // ── Brands ──────────────────────────────────────────────
        $brands = collect([
            'Toyota Genuine Parts',
            'Honda Original',
            'Denso',
            'NGK',
            'Bosch',
            'Aisin',
        ])->map(fn ($nama) => Brand::create([
            'nama' => $nama,
            'slug' => Str::slug($nama),
        ]));

        // ── Kategori ────────────────────────────────────────────
        $kategoris = collect([
            'Mesin',
            'Kelistrikan',
            'Rem',
            'Suspensi',
            'Body & Eksterior',
            'Oli & Cairan',
            'Filter',
        ])->map(fn ($nama) => Kategori::create([
            'nama' => $nama,
            'slug' => Str::slug($nama),
        ]));

        // ── Produk ──────────────────────────────────────────────
        // Daftar keyword untuk mencari gambar yang relevan di Unsplash
        $produkData = [
            [
                'kode' => 'SP-001',
                'nama' => 'Kampas Rem Depan Toyota Avanza',
                'deskripsi' => 'Kampas rem depan original untuk Toyota Avanza 2015-2023. Material berkualitas tinggi untuk pengereman optimal.',
                'harga' => 285000,
                'stok' => 25,
                'brand_id' => $brands[0]->id,
                'kategori_id' => $kategoris[2]->id,
                'keyword' => 'brake-pad',
            ],
            [
                'kode' => 'SP-002',
                'nama' => 'Busi Iridium NGK',
                'deskripsi' => 'Busi iridium performa tinggi untuk mesin bensin. Daya tahan lama dan pembakaran sempurna.',
                'harga' => 95000,
                'stok' => 100,
                'brand_id' => $brands[3]->id,
                'kategori_id' => $kategoris[0]->id,
                'keyword' => 'spark-plug',
            ],
            [
                'kode' => 'SP-003',
                'nama' => 'Filter Oli Honda Jazz',
                'deskripsi' => 'Filter oli original Honda untuk Jazz GK5. Menjaga kebersihan oli mesin.',
                'harga' => 65000,
                'stok' => 50,
                'brand_id' => $brands[1]->id,
                'kategori_id' => $kategoris[6]->id,
                'keyword' => 'oil-filter',
            ],
            [
                'kode' => 'SP-004',
                'nama' => 'Alternator Denso 12V',
                'deskripsi' => 'Alternator 12V original Denso. Cocok untuk berbagai tipe mobil Toyota dan Daihatsu.',
                'harga' => 1750000,
                'stok' => 8,
                'brand_id' => $brands[2]->id,
                'kategori_id' => $kategoris[1]->id,
                'keyword' => 'alternator',
            ],
            [
                'kode' => 'SP-005',
                'nama' => 'Shock Absorber Depan Avanza',
                'deskripsi' => 'Shock absorber depan kualitas OEM untuk Toyota Avanza. Kenyamanan berkendara terjamin.',
                'harga' => 450000,
                'stok' => 15,
                'brand_id' => $brands[0]->id,
                'kategori_id' => $kategoris[3]->id,
                'keyword' => 'shock-absorber',
            ],
            [
                'kode' => 'SP-006',
                'nama' => 'Wiper Blade Bosch 18 inch',
                'deskripsi' => 'Wiper blade premium Bosch dengan teknologi clear advantage. Bersih sempurna saat hujan.',
                'harga' => 125000,
                'stok' => 40,
                'brand_id' => $brands[4]->id,
                'kategori_id' => $kategoris[4]->id,
                'keyword' => 'car-wiper',
            ],
            [
                'kode' => 'SP-007',
                'nama' => 'Oli Mesin 10W-40 Synthetic',
                'deskripsi' => 'Oli mesin sintetis 10W-40 untuk performa maksimal. Melindungi mesin dari gesekan berlebih.',
                'harga' => 320000,
                'stok' => 60,
                'brand_id' => $brands[4]->id,
                'kategori_id' => $kategoris[5]->id,
                'keyword' => 'motor-oil',
            ],
            [
                'kode' => 'SP-008',
                'nama' => 'Kopling Set Aisin Avanza',
                'deskripsi' => 'Set kopling lengkap (disc, cover, bearing) dari Aisin. Untuk Avanza manual 1.3L & 1.5L.',
                'harga' => 1200000,
                'stok' => 10,
                'brand_id' => $brands[5]->id, // Aisin
                'kategori_id' => $kategoris[0]->id, // Mesin
                'keyword' => 'clutch-kit',
            ],
            [
                'kode' => 'SP-009',
                'nama' => 'Filter Udara Honda Brio',
                'deskripsi' => 'Filter udara original Honda untuk Brio Satya. Menjaga kualitas udara masuk ke mesin.',
                'harga' => 85000,
                'stok' => 35,
                'brand_id' => $brands[1]->id, // Honda
                'kategori_id' => $kategoris[6]->id, // Filter
                'keyword' => 'air-filter',
            ],
            [
                'kode' => 'SP-010',
                'nama' => 'Disc Brake Belakang Toyota Innova',
                'deskripsi' => 'Piringan rem belakang untuk Toyota Innova Reborn. Material besi cor berkualitas tinggi.',
                'harga' => 375000,
                'stok' => 12,
                'brand_id' => $brands[0]->id, // Toyota
                'kategori_id' => $kategoris[2]->id, // Rem
                'keyword' => 'brake-disk',
            ],
            [
                'kode' => 'SP-011',
                'nama' => 'Lampu LED Headlight H4 Bosch',
                'deskripsi' => 'Lampu LED headlight tipe H4 dari Bosch. Pencahayaan super terang 6000K putih.',
                'harga' => 550000,
                'stok' => 20,
                'brand_id' => $brands[4]->id, // Bosch
                'kategori_id' => $kategoris[1]->id, // Kelistrikan
                'keyword' => 'car-headlight',
            ],
            [
                'kode' => 'SP-012',
                'nama' => 'Radiator Coolant Hijau 1L',
                'deskripsi' => 'Cairan pendingin radiator hijau konsentrat 1 liter. Mencegah overheating dan korosi.',
                'harga' => 45000,
                'stok' => 80,
                'brand_id' => $brands[2]->id, // Denso
                'kategori_id' => $kategoris[5]->id, // Oli & Cairan
                'keyword' => 'coolant',
            ],
        ];

        // Pastikan folder storage/produk ada
        $storagePath = storage_path('app/public/produk');
        if (! file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Direktori gambar yang dibundel bersama seeder
        $seedImagesDir = database_path('seeders/images');

        $produkModels = [];
        foreach ($produkData as $data) {
            $keyword = $data['keyword'];
            unset($data['keyword']);

            $filename = 'img_seed_'.$data['kode'].'.png';
            $imagePath = 'produk/'.$filename;
            $fullPath = $storagePath.'/'.$filename;

            // Tier 1: Coba salin dari gambar yang sudah dibundel (database/seeders/images/)
            $bundledFile = $seedImagesDir.'/'.$data['kode'].'.png';
            if (file_exists($bundledFile)) {
                copy($bundledFile, $fullPath);
                $data['gambar'] = $imagePath;
            }
            // Tier 2: Buat gambar placeholder profesional menggunakan PHP GD
            elseif (extension_loaded('gd')) {
                $this->generateProductImage($fullPath, $data['nama'], $keyword);
                $data['gambar'] = $imagePath;
            }

            $produkModels[] = Produk::create($data);
        }

        // ── Pembelian Sample ────────────────────────────────────
        Pembelian::create([
            'invoice_no' => 'INV-20260420-001',
            'user_id' => $customer1->id,
            'produk_id' => $produkModels[0]->id,
            'jumlah' => 1,
            'total' => 285000,
            'status' => 'dikonfirmasi',
        ]);

        Pembelian::create([
            'invoice_no' => 'INV-20260420-002',
            'user_id' => $customer1->id,
            'produk_id' => $produkModels[1]->id,
            'jumlah' => 4,
            'total' => 380000,
            'status' => 'pending',
        ]);

        Pembelian::create([
            'invoice_no' => 'INV-20260420-003',
            'user_id' => $customer2->id,
            'produk_id' => $produkModels[6]->id,
            'jumlah' => 2,
            'total' => 640000,
            'status' => 'dikonfirmasi',
        ]);

        Pembelian::create([
            'invoice_no' => 'INV-20260420-004',
            'user_id' => $customer2->id,
            'produk_id' => $produkModels[3]->id,
            'jumlah' => 1,
            'total' => 1750000,
            'status' => 'ditolak',
        ]);

        Pembelian::create([
            'invoice_no' => 'INV-20260420-005',
            'user_id' => $customer1->id,
            'produk_id' => $produkModels[10]->id,
            'jumlah' => 2,
            'total' => 1100000,
            'status' => 'pending',
        ]);
    }

    /**
     * Buat gambar placeholder profesional via PHP GD.
     * Setiap produk mendapat warna unik berdasarkan keyword-nya.
     */
    private function generateProductImage(string $path, string $productName, string $keyword): void
    {
        $size = 800;
        $img = imagecreatetruecolor($size, $size);

        // Palet warna berdasarkan kategori produk
        $colorMap = [
            'brake-pad' => ['bg' => [220, 38,  38],  'accent' => [254, 202, 202]], // Merah
            'spark-plug' => ['bg' => [234, 179, 8],   'accent' => [254, 249, 195]], // Kuning
            'oil-filter' => ['bg' => [37,  99,  235], 'accent' => [191, 219, 254]], // Biru
            'alternator' => ['bg' => [107, 114, 128], 'accent' => [229, 231, 235]], // Abu
            'shock-absorber' => ['bg' => [22,  163, 74],  'accent' => [187, 247, 208]], // Hijau
            'car-wiper' => ['bg' => [59,  130, 246], 'accent' => [191, 219, 254]], // Biru muda
            'motor-oil' => ['bg' => [245, 158, 11],  'accent' => [254, 243, 199]], // Amber
            'clutch-kit' => ['bg' => [139, 92,  246], 'accent' => [221, 214, 254]], // Ungu
            'air-filter' => ['bg' => [6,   182, 212], 'accent' => [165, 243, 252]], // Cyan
            'brake-disk' => ['bg' => [239, 68,  68],  'accent' => [254, 202, 202]], // Merah terang
            'car-headlight' => ['bg' => [250, 204, 21],  'accent' => [254, 249, 195]], // Kuning terang
            'coolant' => ['bg' => [16,  185, 129], 'accent' => [167, 243, 208]], // Emerald
        ];

        $colors = $colorMap[$keyword] ?? ['bg' => [100, 116, 139], 'accent' => [226, 232, 240]];

        // Warna
        $bgColor = imagecolorallocate($img, $colors['accent'][0], $colors['accent'][1], $colors['accent'][2]);
        $primaryColor = imagecolorallocate($img, $colors['bg'][0], $colors['bg'][1], $colors['bg'][2]);
        $white = imagecolorallocate($img, 255, 255, 255);
        $textDark = imagecolorallocate($img, 30, 41, 59);

        // Background
        imagefill($img, 0, 0, $bgColor);

        // Lingkaran dekoratif besar di tengah
        $cx = $size / 2;
        $cy = $size / 2 - 40;
        $radius = 220;
        imagefilledellipse($img, (int) $cx, (int) $cy, $radius * 2, $radius * 2, $white);

        // Ikon gear/wrench sederhana di tengah lingkaran
        imagefilledellipse($img, (int) $cx, (int) $cy, 120, 120, $primaryColor);
        imagefilledellipse($img, (int) $cx, (int) $cy, 60, 60, $white);

        // Garis dekoratif
        imagesetthickness($img, 3);
        imageline($img, (int) $cx - 80, (int) $cy, (int) $cx - 35, (int) $cy, $primaryColor);
        imageline($img, (int) $cx + 35, (int) $cy, (int) $cx + 80, (int) $cy, $primaryColor);
        imageline($img, (int) $cx, (int) $cy - 80, (int) $cx, (int) $cy - 35, $primaryColor);
        imageline($img, (int) $cx, (int) $cy + 35, (int) $cx, (int) $cy + 80, $primaryColor);

        // Bar bawah untuk nama produk
        imagefilledrectangle($img, 0, $size - 160, $size, $size, $primaryColor);

        // Nama produk (potong jika kepanjangan)
        $displayName = mb_strlen($productName) > 28
            ? mb_substr($productName, 0, 28).'...'
            : $productName;

        // Gunakan font bawaan GD (size 5 = terbesar)
        $fontWidth = imagefontwidth(5);
        $textWidth = $fontWidth * strlen($displayName);
        $textX = max(20, (int) (($size - $textWidth) / 2));
        $textY = $size - 110;
        imagestring($img, 5, $textX, $textY, $displayName, $white);

        // Kode produk kecil di bawah nama
        $codeText = strtoupper($keyword);
        $codeWidth = imagefontwidth(3) * strlen($codeText);
        $codeX = max(20, (int) (($size - $codeWidth) / 2));
        imagestring($img, 3, $codeX, $textY + 30, $codeText, imagecolorallocatealpha($img, 255, 255, 255, 50));

        // Simpan sebagai PNG
        imagepng($img, $path, 6); // Kualitas kompresi 6 (0-9, 0=terbesar)
        imagedestroy($img);
    }
}
