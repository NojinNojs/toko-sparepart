<x-admin-layout>
    <x-slot:header>Dashboard Kelola Toko</x-slot:header>

    {{-- Dashboard Container with Auto-Polling --}}
    <div x-data="dashboardData()" x-init="startPolling()" class="space-y-6">

        {{-- Live Indicator --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Data realtime — diperbarui otomatis</span>
            </div>
            <span class="text-xs text-slate-400 font-mono hidden sm:block" x-text="'Update: ' + lastUpdate"></span>
        </div>

        {{-- ==================== ROW 1: STAT CARDS ==================== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <x-stat-card
                color="blue"
                :value="$stats['total_produk']"
                label="Total Produk"
                :href="route('admin.produk.index')"
                icon='<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'
            />
            <x-stat-card
                color="green"
                :value="'Rp ' . number_format($stats['pendapatan'], 0, ',', '.')"
                label="Pendapatan"
                icon='<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            />
            <x-stat-card
                color="yellow"
                :value="$stats['pesanan_pending']"
                label="Menunggu Konfirmasi"
                :href="route('admin.pembelian.index', ['status' => 'pending'])"
                icon='<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            />
            <x-stat-card
                color="purple"
                :value="$stats['total_customer']"
                label="Total Pelanggan"
                icon='<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'
            />
        </div>

        {{-- ==================== ROW 2: CHARTS ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Revenue Chart (2/3) --}}
            <div class="lg:col-span-2 card p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-800">Pendapatan 7 Hari Terakhir</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Hanya transaksi yang dikonfirmasi</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span class="hidden sm:inline">Grafik Harian</span>
                    </div>
                </div>
                <div class="relative" style="height: 220px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Status Donut Chart (1/3) --}}
            <div class="card p-4 sm:p-6">
                <div class="mb-4">
                    <h2 class="text-base sm:text-lg font-bold text-slate-800">Status Pesanan</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Ringkasan seluruh transaksi</p>
                </div>
                <div class="relative mx-auto" style="height: 180px; max-width: 180px;">
                    <canvas id="statusChart"></canvas>
                </div>
                {{-- Legend --}}
                <div class="mt-4 space-y-2">
                    @foreach($chartStatus as $s)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $s['color'] }}"></span>
                                <span class="text-slate-600 text-xs sm:text-sm">{{ $s['label'] }}</span>
                            </div>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm">{{ $s['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ==================== ROW 3: TABLES ==================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">

            {{-- Transaksi Terbaru (2/3) --}}
            <div class="xl:col-span-2 card overflow-hidden">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                    <h2 class="text-sm sm:text-base font-bold text-slate-800">Transaksi Terbaru</h2>
                    <a href="{{ route('admin.pembelian.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if($transaksiTerbaru->count() > 0)
                    {{-- Desktop Table --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="table-clean">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksiTerbaru as $trx)
                                    <tr>
                                        <td class="font-mono text-xs text-slate-500 font-medium">{{ $trx->invoice_no }}</td>
                                        <td class="text-slate-800 font-medium text-sm">{{ $trx->user->name ?? '-' }}</td>
                                        <td class="text-slate-600 text-sm">{{ Str::limit($trx->produk->nama ?? '-', 25) }}</td>
                                        <td class="text-center text-slate-600 text-sm">{{ $trx->jumlah }}</td>
                                        <td class="text-right font-bold text-slate-900 text-sm">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <x-status-badge :status="$trx->status" />
                                        </td>
                                        <td class="text-right text-xs text-slate-400">{{ $trx->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards (visible on small screens) --}}
                    <div class="sm:hidden divide-y divide-slate-100">
                        @foreach($transaksiTerbaru as $trx)
                            <div class="p-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono text-xs text-primary-600 font-bold bg-primary-50 px-2 py-0.5 rounded">{{ $trx->invoice_no }}</span>
                                    <x-status-badge :status="$trx->status" />
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-800">{{ $trx->user->name ?? '-' }}</p>
                                        <p class="text-xs text-slate-500">{{ Str::limit($trx->produk->nama ?? '-', 30) }} × {{ $trx->jumlah }}</p>
                                    </div>
                                    <p class="text-sm font-bold text-slate-900">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                                </div>
                                <p class="text-xs text-slate-400">{{ $trx->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-slate-500">Belum ada transaksi</p>
                    </div>
                @endif
            </div>

            {{-- Stok Rendah Alert (1/3) --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-slate-200 bg-red-50/50">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <h2 class="text-sm sm:text-base font-bold text-red-800">Peringatan Stok</h2>
                    </div>
                    <span class="badge badge-danger text-xs">{{ $stokRendah->count() }} produk</span>
                </div>

                @if($stokRendah->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($stokRendah as $item)
                            <a href="{{ route('admin.produk.edit', $item) }}" class="flex items-center gap-3 px-4 sm:px-5 py-3 hover:bg-slate-50 transition-colors group">
                                {{-- Thumbnail --}}
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="" class="w-10 h-10 rounded-lg object-contain bg-white border border-slate-200 flex-shrink-0" loading="lazy">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 border border-slate-200">
                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate group-hover:text-primary-600 transition-colors">{{ $item->nama }}</p>
                                    <p class="text-xs text-slate-400">{{ $item->brand->nama ?? '' }}</p>
                                </div>
                                {{-- Stok Badge --}}
                                <span class="flex-shrink-0 px-2 py-1 rounded-md text-xs font-bold {{ $item->stok == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $item->stok == 0 ? 'Habis' : $item->stok . ' sisa' }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 mx-auto bg-emerald-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-sm text-slate-500">Semua stok aman 👍</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ==================== ROW 4: PRODUK TERLARIS + QUICK ACTIONS ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

            {{-- Produk Terlaris --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                    <h2 class="text-sm sm:text-base font-bold text-slate-800">🏆 Produk Terlaris</h2>
                </div>
                @if($produkTerlaris->where('total_terjual', '>', 0)->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($produkTerlaris->where('total_terjual', '>', 0) as $index => $p)
                            <div class="flex items-center gap-3 px-4 sm:px-5 py-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                                    {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-700') }}">
                                    {{ $index + 1 }}
                                </span>
                                @if($p->gambar)
                                    <img src="{{ asset('storage/' . $p->gambar) }}" alt="" class="w-10 h-10 rounded-lg object-contain bg-white border border-slate-200 flex-shrink-0" loading="lazy">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 border border-slate-200">
                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate">{{ $p->nama }}</p>
                                </div>
                                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded flex-shrink-0">{{ $p->total_terjual }} terjual</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <p class="text-sm text-slate-500">Belum ada data penjualan</p>
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="card p-4 sm:p-6">
                <h2 class="text-sm sm:text-base font-bold text-slate-800 mb-4">⚡ Aksi Cepat</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.produk.create') }}" class="flex flex-col items-center justify-center gap-2 p-4 bg-primary-50 rounded-xl hover:bg-primary-100 transition-colors group border border-primary-100">
                        <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-primary-700 text-center">Tambah Produk</span>
                    </a>
                    <a href="{{ route('admin.pembelian.index', ['status' => 'pending']) }}" class="flex flex-col items-center justify-center gap-2 p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors group border border-amber-100">
                        <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-amber-700 text-center">Proses Pesanan</span>
                    </a>
                    <a href="{{ route('admin.brand.create') }}" class="flex flex-col items-center justify-center gap-2 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group border border-blue-100">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-blue-700 text-center">Tambah Merek</span>
                    </a>
                    <a href="{{ route('admin.kategori.create') }}" class="flex flex-col items-center justify-center gap-2 p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors group border border-emerald-100">
                        <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-700 text-center">Tambah Kategori</span>
                    </a>
                </div>

                {{-- Summary Row --}}
                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                    <div>
                        <p class="text-lg font-bold text-slate-800">{{ $stats['total_brand'] }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Merek</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-800">{{ $stats['total_kategori'] }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Kategori</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-800">{{ $stats['total_transaksi'] }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Transaksi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>

    <script>
        // === Chart.js Initialization ===
        document.addEventListener('DOMContentLoaded', function () {
            // Revenue Bar Chart
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                window.revenueChartInstance = new Chart(revenueCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartPendapatan->pluck('label')),
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: @json($chartPendapatan->pluck('value')),
                            backgroundColor: 'rgba(220, 38, 38, 0.15)',
                            borderColor: 'rgba(220, 38, 38, 0.8)',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                                },
                                backgroundColor: '#1e293b',
                                titleFont: { weight: 'bold', size: 12 },
                                bodyFont: { size: 13 },
                                padding: 12,
                                cornerRadius: 8,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (val) => val >= 1000000 ? (val / 1000000).toFixed(1) + ' jt' : (val >= 1000 ? (val / 1000).toFixed(0) + ' rb' : val),
                                    font: { size: 11, weight: '500' },
                                    color: '#94a3b8',
                                },
                                grid: { color: '#f1f5f9', drawBorder: false },
                                border: { display: false },
                            },
                            x: {
                                ticks: { font: { size: 11, weight: '600' }, color: '#64748b' },
                                grid: { display: false },
                                border: { display: false },
                            }
                        },
                    }
                });
            }

            // Status Donut Chart
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                const statusData = @json($chartStatus);
                const total = statusData.reduce((a, b) => a + b.value, 0);
                window.statusChartInstance = new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusData.map(s => s.label),
                        datasets: [{
                            data: statusData.map(s => s.value),
                            backgroundColor: statusData.map(s => s.color),
                            borderWidth: 0,
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { weight: 'bold', size: 12 },
                                bodyFont: { size: 13 },
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: (ctx) => {
                                        const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                        return ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });

        // === Alpine.js: Auto-polling every 15 seconds ===
        function dashboardData() {
            return {
                lastUpdate: new Date().toLocaleTimeString('id-ID'),
                pollInterval: null,
                startPolling() {
                    this.pollInterval = setInterval(() => this.fetchData(), 15000);
                },
                async fetchData() {
                    try {
                        const res = await fetch('{{ route("admin.dashboard.poll") }}', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });
                        if (!res.ok) return;
                        const data = await res.json();
                        this.lastUpdate = data.timestamp;

                        // Update revenue chart
                        if (window.revenueChartInstance && data.chartPendapatan) {
                            window.revenueChartInstance.data.labels = data.chartPendapatan.map(d => d.label);
                            window.revenueChartInstance.data.datasets[0].data = data.chartPendapatan.map(d => d.value);
                            window.revenueChartInstance.update('none');
                        }

                        // Update status chart
                        if (window.statusChartInstance && data.chartStatus) {
                            window.statusChartInstance.data.datasets[0].data = data.chartStatus.map(s => s.value);
                            window.statusChartInstance.update('none');
                        }
                    } catch (e) {
                        // Silently fail — polling will retry on next interval
                    }
                },
                destroy() {
                    if (this.pollInterval) clearInterval(this.pollInterval);
                }
            };
        }
    </script>
</x-admin-layout>
