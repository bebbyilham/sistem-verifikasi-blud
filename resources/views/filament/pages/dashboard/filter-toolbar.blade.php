<div x-data="{ 
    customOpen: false, 
    viewMode: 'timeline'
}" class="w-full my-2">

    <!-- Filter Bar Header / Main Toolbar (Transparent Container, No White Box) -->
    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; width: 100%; padding: 4px 0; background: transparent; border: none; box-shadow: none;" class="period-filter-toolbar">
        
        <!-- Left Pill Container: Timeline / List view toggle -->
        <div style="display: inline-flex; align-items: center; padding: 3px; background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 2px 6px rgba(99, 102, 241, 0.05); gap: 2px;" class="period-filter-pill-group">
            <button 
                type="button" 
                @click="viewMode = 'timeline'; $wire.set('filters.view', 'timeline')"
                :style="viewMode === 'timeline' 
                    ? 'background: #ffffff; color: #0f172a; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 14px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                class="period-filter-pill-btn">
                Timeline
            </button>
            <button 
                type="button" 
                @click="viewMode = 'list'; $wire.set('filters.view', 'list')"
                :style="viewMode === 'list' 
                    ? 'background: #ffffff; color: #0f172a; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 14px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                class="period-filter-pill-btn">
                List
            </button>
        </div>

        <!-- Right Pill Container: Period Presets -->
        <div style="display: inline-flex; align-items: center; padding: 3px; background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 2px 6px rgba(99, 102, 241, 0.05); gap: 2px;" class="period-filter-pill-group">
            
            <!-- 1D -->
            <button 
                type="button" 
                @click="$wire.setPeriodPreset('1D')"
                :style="$wire.filters?.period === '1D' 
                    ? 'background: #ffffff; color: #0f172a; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 12px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                class="period-filter-pill-btn">
                1D
            </button>

            <!-- 7D -->
            <button 
                type="button" 
                @click="$wire.setPeriodPreset('7D')"
                :style="($wire.filters?.period === '7D' || !$wire.filters?.period) 
                    ? 'background: #ffffff; color: #0f172a; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 12px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                class="period-filter-pill-btn">
                7D
            </button>

            <!-- 1M -->
            <button 
                type="button" 
                @click="$wire.setPeriodPreset('1M')"
                :style="$wire.filters?.period === '1M' 
                    ? 'background: #ffffff; color: #0f172a; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 12px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                class="period-filter-pill-btn">
                1M
            </button>

            <!-- 3M -->
            <button 
                type="button" 
                @click="$wire.setPeriodPreset('3M')"
                :style="$wire.filters?.period === '3M' 
                    ? 'background: #ffffff; color: #0f172a; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 12px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                class="period-filter-pill-btn">
                3M
            </button>

            <!-- YTD -->
            <button 
                type="button" 
                @click="$wire.setPeriodPreset('YTD')"
                :style="$wire.filters?.period === 'YTD' 
                    ? 'background: #ffffff; color: #0f172a; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 12px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                class="period-filter-pill-btn">
                YTD
            </button>

            <!-- Calendar Icon (Custom Date Range Toggle) -->
            <button 
                type="button" 
                @click="customOpen = !customOpen; if(customOpen) $wire.setPeriodPreset('custom')"
                :style="($wire.filters?.period === 'custom' || customOpen)
                    ? 'background: #ffffff; color: #4f46e5; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 10px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center;"
                title="Pilih Rentang Tanggal Custom"
                class="period-filter-pill-btn">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </button>

            <!-- All time -->
            <button 
                type="button" 
                @click="$wire.setPeriodPreset('all')"
                :style="$wire.filters?.period === 'all' 
                    ? 'background: #ffffff; color: #0f172a; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.08);' 
                    : 'background: transparent; color: #64748b; font-weight: 500;'"
                style="padding: 6px 12px; font-size: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;"
                class="period-filter-pill-btn">
                All time
            </button>
        </div>
    </div>

    <!-- Collapsible Custom Date Range Picker Container -->
    <div x-show="customOpen || $wire.filters?.period === 'custom'" x-collapse x-cloak 
         style="margin-top: 10px; padding: 16px 20px; background: rgba(255, 255, 255, 0.95); border-radius: 16px; border: 1px solid rgba(99, 102, 241, 0.15); box-shadow: 0 4px 14px rgba(99, 102, 241, 0.08);"
         class="period-filter-custom-panel">
        <div style="display: flex; gap: 16px; align-items: center;" class="flex flex-col sm:flex-row">
            <div style="flex: 1; width: 100%;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px;">Tanggal Mulai Ajuan</label>
                <input 
                    type="date" 
                    wire:model.live="filters.startDate"
                    style="width: 100%; font-size: 12px; padding: 8px 12px; border-radius: 10px; border: 1px solid #d1d5db; background: #ffffff; color: #111827;"
                />
            </div>
            <div style="flex: 1; width: 100%;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px;">Tanggal Selesai Ajuan</label>
                <input 
                    type="date" 
                    wire:model.live="filters.endDate"
                    style="width: 100%; font-size: 12px; padding: 8px 12px; border-radius: 10px; border: 1px solid #d1d5db; background: #ffffff; color: #111827;"
                />
            </div>
        </div>
        <div style="margin-top: 8px; text-align: right;">
            <button 
                type="button" 
                @click="customOpen = false"
                style="font-size: 12px; color: #6b7280; background: none; border: none; text-decoration: underline; cursor: pointer;">
                Tutup Tanggal Kustom
            </button>
        </div>
    </div>

</div>
