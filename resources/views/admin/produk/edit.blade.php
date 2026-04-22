<x-admin-layout>
    <x-slot:header>Edit Produk: {{ $produk->nama }}</x-slot:header>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="card overflow-hidden">
            <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight">Perbarui Data Produk</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Ubah spesifikasi atau detail produk « <span class="font-semibold text-slate-500">{{ $produk->nama }}</span> »</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.produk.update', $produk) }}" enctype="multipart/form-data" 
                  x-data="{ 
                    isDragging: false, 
                    currentImage: '{{ $produk->gambar ? asset('storage/' . $produk->gambar) : '' }}',
                    imagePreview: null,
                    showCropper: false,
                    cropper: null,
                    croppedImage: null,
                    
                    initCropper() {
                        if(this.cropper) { this.cropper.destroy(); }
                        this.cropper = new Cropper(this.$refs.cropImage, {
                            aspectRatio: 1, // Persegi
                            viewMode: 1,
                            autoCropArea: 1,
                        });
                    },
                    
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => { 
                                this.imagePreview = e.target.result;
                                this.showCropper = true;
                                this.$nextTick(() => { this.initCropper(); });
                            };
                            reader.readAsDataURL(file);
                        }
                    },
                    handleDrop(event) {
                        this.isDragging = false;
                        const file = event.dataTransfer.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const input = this.$refs.fileInput;
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            input.files = dataTransfer.files;
                            this.handleFileSelect({ target: { files: [file] } });
                        }
                    },
                    applyCrop() {
                        if (!this.cropper) return;
                        this.croppedImage = this.cropper.getCroppedCanvas({
                            width: 800,
                            height: 800
                        }).toDataURL('image/jpeg', 0.9);
                        this.showCropper = false;
                        this.cropper.destroy();
                        this.cropper = null;
                        this.$refs.croppedInput.value = this.croppedImage;
                    },
                    cancelCrop() {
                        this.showCropper = false;
                        if(this.cropper) {
                            this.cropper.destroy();
                            this.cropper = null;
                        }
                        this.imagePreview = null;
                        this.$refs.fileInput.value = '';
                    }
                  }"
                  class="p-8">
                @csrf
                @method('PATCH')

                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Kode Produk --}}
                        <div>
                            <x-input-label for="kode" value="Kode Produk" />
                            <input type="text" id="kode" name="kode" value="{{ old('kode', $produk->kode) }}"
                                   class="form-input-clean" required>
                            @error('kode') <p class="form-error"> <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                        </div>

                        {{-- Nama Produk --}}
                        <div>
                            <x-input-label for="nama" value="Nama Produk" />
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $produk->nama) }}"
                                   class="form-input-clean" required>
                            @error('nama') <p class="form-error"> <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                        </div>

                        {{-- Harga --}}
                        <div>
                            <x-input-label for="harga" value="Harga (Rp)" />
                            <div class="relative group" x-data="{
                                raw: '{{ old('harga', $produk->harga) }}',
                                formatted: '',
                                init() {
                                    if(this.raw) this.formatted = parseInt(this.raw).toLocaleString('id-ID');
                                },
                                updateValue(e) {
                                    let val = e.target.value.replace(/[^0-9]/g, '');
                                    if (val) {
                                        val = parseInt(val, 10).toString();
                                        this.raw = val;
                                        this.formatted = parseInt(val, 10).toLocaleString('id-ID');
                                    } else {
                                        this.raw = '';
                                        this.formatted = '';
                                    }
                                }
                            }">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm group-focus-within:text-primary-500 transition-colors">Rp</span>
                                <input type="text" x-model="formatted" @input="updateValue"
                                       class="form-input-clean pl-12" required>
                                <input type="hidden" name="harga" x-model="raw">
                            </div>
                            @error('harga') <p class="form-error"> <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                        </div>

                        {{-- Stok --}}
                        <div x-data="{
                                raw: '{{ old('stok', $produk->stok) }}',
                                init() {
                                    if(this.raw && this.raw !== '0') this.raw = parseInt(this.raw, 10).toString();
                                },
                                updateValue(e) {
                                    let val = e.target.value.replace(/[^0-9]/g, '');
                                    if(val) val = parseInt(val, 10).toString();
                                    this.raw = val;
                                }
                            }">
                            <x-input-label for="stok" value="Persediaan Stok" />
                            <input type="text" x-model="raw" @input="updateValue"
                                   placeholder="0" class="form-input-clean" required>
                            <input type="hidden" name="stok" x-model="raw">
                            @error('stok') <p class="form-error"> <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                        </div>

                        {{-- Brand --}}
                        <div>
                            <x-input-label for="brand_id" value="Merek / Brand" />
                            <x-custom-select 
                                name="brand_id" 
                                id="brand_id" 
                                :options="$brands->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama])" 
                                :selected="$produk->brand_id" 
                                placeholder="Pilih merek..." 
                                required 
                            />
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <x-input-label for="kategori_id" value="Kategori Produk" />
                            <x-custom-select 
                                name="kategori_id" 
                                id="kategori_id" 
                                :options="$kategoris->map(fn($k) => ['id' => $k->id, 'nama' => $k->nama])" 
                                :selected="$produk->kategori_id" 
                                placeholder="Pilih kategori..." 
                                required 
                            />
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <x-input-label for="deskripsi" value="Deskripsi Produk" />
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                                  class="form-textarea-clean">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    </div>

                    {{-- Gambar --}}
                    <div>
                        <x-input-label for="gambar" value="Foto / Gambar Produk (1:1)" />
                        
                        <div class="upload-zone" 
                             :class="{ 'drag-over': isDragging }"
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="handleDrop($event)">
                            
                            <div class="space-y-4 text-center w-full">
                                <template x-if="!croppedImage && !currentImage">
                                    <div class="py-4">
                                        <svg class="mx-auto h-12 w-12 text-slate-300 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4 flex text-sm justify-center text-slate-600">
                                            <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-bold text-primary-600 hover:text-primary-500 transition-colors focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500 px-1">
                                                <span>Ganti gambar</span>
                                                <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*" 
                                                       x-ref="fileInput" @change="handleFileSelect($event)">
                                            </label>
                                            <p class="pl-1">atau seret file baru ke sini</p>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="croppedImage || currentImage">
                                    <div class="relative inline-block mt-2 group">
                                        <img :src="croppedImage ? croppedImage : currentImage" class="max-h-48 rounded-xl border border-slate-200 shadow-sm mx-auto">
                                        <button type="button" @click="croppedImage = null; currentImage = null; $refs.fileInput.value = ''; $refs.croppedInput.value = ''" 
                                                class="absolute -top-3 -right-3 bg-red-500 text-white p-1.5 rounded-full shadow-lg hover:bg-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        <div class="mt-4 flex text-sm justify-center text-slate-600 w-full" x-show="currentImage && !croppedImage">
                                            <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-bold text-primary-600 hover:text-primary-500 transition-colors focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500 px-1">
                                                <span>Klik untuk Ganti Gambar</span>
                                                <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*" 
                                                       x-ref="fileInput" @change="handleFileSelect($event)">
                                            </label>
                                        </div>
                                        <p class="mt-2 text-xs font-bold text-slate-500" x-text="croppedImage ? 'Gambar yang Selesai di-Crop' : 'Gambar Saat Ini'"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <input type="hidden" name="cropped_image" x-ref="croppedInput" x-model="croppedImage">
                        @error('gambar') <p class="form-error"> <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror

                        <!-- Modal Cropper -->
                        <div x-show="showCropper" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="cancelCrop">
                                    <div class="absolute inset-0 bg-slate-900 opacity-75"></div>
                                </div>
                                <div class="relative align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                                    <div class="text-center mb-4">
                                        <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Sesuaikan Gambar (Crop 1:1)</h3>
                                        <p class="text-sm text-slate-500 mt-2">Geser dan perbesar untuk menyesuaikan area gambar.</p>
                                    </div>
                                    <div class="w-full bg-slate-100 flex items-center justify-center p-2 rounded max-h-[60vh] overflow-hidden">
                                        <div style="max-height: 50vh;">
                                            <img x-ref="cropImage" :src="imagePreview" alt="Preview" class="max-w-full" style="display: block; max-height: 100%;">
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                                        <button type="button" class="btn-primary w-full sm:w-auto px-6 mb-3 sm:mb-0" @click="applyCrop">
                                            Simpan Potongan
                                        </button>
                                        <button type="button" class="btn-secondary w-full sm:w-auto px-6" @click="cancelCrop">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal Cropper -->
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-12 pt-8 border-t border-slate-100">
                    <a href="{{ route('admin.produk.index') }}" class="btn-secondary px-8">Batal</a>
                    <button type="submit" class="btn-primary px-8 shadow-lg shadow-primary-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
