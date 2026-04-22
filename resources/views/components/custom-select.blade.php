@props([
    'name', 
    'id' => null, 
    'options' => [], 
    'selected' => null, 
    'placeholder' => 'Pilih opsi...',
    'required' => false
])

<div 
    x-data="{
        open: false,
        search: '',
        value: '{{ old($name, $selected) }}',
        label: '',
        options: {{ json_encode($options) }},
        placeholder: '{{ $placeholder }}',
        
        init() {
            const selectedOption = this.options.find(opt => opt.id == this.value);
            this.label = selectedOption ? selectedOption.nama : this.placeholder;
        },

        get filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(opt => 
                opt.nama.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        select(id, nama) {
            this.value = id;
            this.label = nama;
            this.open = false;
            this.search = '';
        }
    }"
    @click.away="open = false"
    class="relative"
>
    <!-- Hidden Input for Form Submission -->
    <input type="hidden" name="{{ $name }}" :value="value" @if($required) required @endif id="{{ $id ?? $name }}">

    <!-- Trigger -->
    <button 
        type="button"
        @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
        class="form-select-clean text-left flex items-center justify-between group !bg-none"
        :class="{ 'border-primary-400 ring-2 ring-primary-500/5': open }"
    >
        <span x-text="label" :class="{ 'text-slate-400': label === placeholder, 'text-slate-900': label !== placeholder }"></span>
        <svg class="w-5 h-5 text-slate-400 group-hover:text-primary-500 transition-colors duration-200" 
             :class="{ 'rotate-180': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown Panel -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-[-10px]"
        class="absolute z-[999] mt-2 w-full bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden"
        style="display: none;"
    >
        <!-- Search Box -->
        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
            <div class="relative group">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    x-ref="searchInput"
                    type="text" 
                    x-model="search"
                    placeholder="Cari..." 
                    class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-500/5 transition-all outline-none"
                    @keydown.escape="open = false"
                >
            </div>
        </div>

        <!-- Options list -->
        <div class="max-h-60 overflow-y-auto py-2 custom-scrollbar">
            <template x-if="filteredOptions.length === 0">
                <div class="px-4 py-3 text-sm text-slate-500 italic text-center">
                    Data tidak ditemukan...
                </div>
            </template>

            <template x-for="option in filteredOptions" :key="option.id">
                <button 
                    type="button"
                    @click="select(option.id, option.nama)"
                    class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center justify-between"
                    :class="{ 'bg-primary-50 text-primary-700 font-bold': value == option.id, 'text-slate-700 hover:bg-slate-50 hover:pl-6': value != option.id }"
                >
                    <span x-text="option.nama"></span>
                    <svg x-show="value == option.id" class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </template>
        </div>
    </div>
</div>
