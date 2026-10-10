@props([
    'value' => null,
    'placeholder' => 'Select date',
    'format' => 'Y-m-d',
    'range' => false,
    'rangeStart' => null,
    'rangeEnd' => null,
    'showTime' => false,
    'time' => null,
    'floating' => false,
    'anchor' => null,
])

<div
    {{ $attributes->merge(['class' => 'erp-date-picker']) }}
    x-data="{
        open: false,
        floating: @js($floating),
        value: @js($value),
        rangeMode: @js($range),
        rangeStart: @js($rangeStart),
        rangeEnd: @js($rangeEnd),
        showTime: @js($showTime),
        time: @js($time ?? ($showTime && $value ? \Carbon\Carbon::parse($value)->format('H:i') : '12:00')),
        get display() {
            if (this.rangeMode) {
                if (!this.rangeStart) return '';
                const fmt = (d) => {
                    const dt = new Date(d + 'T00:00:00');
                    return dt.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                };
                if (this.rangeEnd) return fmt(this.rangeStart) + ' â†’ ' + fmt(this.rangeEnd);
                return fmt(this.rangeStart) + ' â†’ ...';
            }
            if (!this.value) return '';
            const d = new Date(this.value + 'T00:00:00');
            let str = d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            if (this.showTime) str += ' ' + this.time;
            return str;
        },
        positionDropdown() {
            if (!this.floating) {
                return;
            }

            const el = this.$refs.dropdown;
            if (!el || !this.$refs.trigger) {
                return;
            }

            const rect = this.$refs.trigger.getBoundingClientRect();
            el.style.top = (rect.bottom + 4) + 'px';
            el.style.left = rect.left + 'px';
            el.style.minWidth = rect.width + 'px';
        },
        toggleOpen() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.positionDropdown());
            }
        },
        selectDate(e) {
            this.value = e.detail.date;
            if (!this.showTime) this.open = false;
            this.$dispatch('input', this.showTime ? this.value + ' ' + this.time : this.value);
        },
        selectRange(e) {
            this.rangeStart = e.detail.start;
            this.rangeEnd = e.detail.end;
            this.open = false;
            this.$dispatch('range-input', { start: this.rangeStart, end: this.rangeEnd });
        },
        updateTime(val) {
            this.time = val;
            if (this.value) {
                this.$dispatch('input', this.value + ' ' + this.time);
            }
        },
        init() {
            this.$watch('open', value => {
                if (value) {
                    this.$nextTick(() => this.positionDropdown());
                }
            });
        },
    }"
    x-on:date-selected.stop="selectDate"
    x-on:range-selected.stop="selectRange"
    x-on:resize.window="if (open && floating) positionDropdown()"
    x-on:scroll.window="if (open && floating) positionDropdown()"
>
    <div class="erp-date-picker-input" x-ref="trigger" x-on:click="toggleOpen()">
        <x-dense::icons.calendar size="5" />
        <input type="text" readonly x-bind:value="display" placeholder="{{ $placeholder }}">
    </div>
    <div
        x-ref="dropdown"
        @class([
            'erp-date-picker-dropdown',
            'erp-date-picker-dropdown-floating' => $floating,
        ])
        x-show="open"
        x-on:click.outside="open = false"
        x-cloak
    >
        @if($range)
            <x-dense::calendar :range-start="$rangeStart" :range-end="$rangeEnd" :anchor="$anchor" />
        @else
            <x-dense::calendar :selected="$value" :anchor="$anchor" />
        @endif
        @if($showTime)
            <div style="padding: 8px 12px; border-top: 1px solid var(--card-border); display: flex; align-items: center; gap: 8px; background: var(--card-bg);">
                <x-dense::icons.clock size="4" style="width: 14px; height: 14px; color: var(--text-muted);" />
                <input type="time" class="erp-date-picker-time" x-bind:value="time" x-on:input="updateTime($event.target.value)" style="flex: 1;">
            </div>
        @endif
    </div>
</div>
