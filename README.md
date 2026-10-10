# Dense UI

`dense/ui` is a Laravel-first, domain-neutral component package for
dense operational interfaces.

## Installation

```bash
composer require dense/ui
```

The package serves compiled assets through Laravel. The generated URLs include
the distribution file modification time as a version query and the responses
use long-lived immutable cache headers. Normal consumers do not need a second
npm package or vendor publishing.

Add the directives once in the application layout:

```blade
@denseStyles
...
@denseScripts
```

Dense expects the host application to provide Alpine. Dense registers its
behaviors against that runtime; it does not bundle Alpine and never calls
`Alpine.start()`.

## Public API

Blade components are the primary API:

```blade
<x-dense::input wire:model.live="assetNumber" />
<x-dense::combobox :options="$products" model="productId" />
```

Components preserve relevant `wire:*`, `x-*`, `aria-*`, `data-*`, `id`, and
`class` attributes.

## Architecture

```text
Application
|- Laravel / Livewire -> server state, validation, and domain Actions
`- Host Alpine runtime
        |
        v
  registerDense(Alpine)
        |- Dropdown
        |- Combobox
        |- Dialog
        `- Tabs
                |
                v
        Dense Blade components -> server-rendered HTML
```

Alpine owns only ephemeral UI state: open/closed state, highlighted options,
keyboard handling, and focus management. Livewire owns search requests,
selected domain values, authorization, and validation. Dense contains no
Asset, Container, StockLevel, Store, or other application domain rules.

## Interactive components

- `combobox`: selection, filtering, keyboard navigation, disabled options,
  and optional Livewire model synchronization;
- `dropdown`: open/close, Escape, outside close, menu focus, and focus return;
- `dialog`: open/close, Escape, initial focus, focus trap, and focus return;
- `tabs`: active tab state and arrow/Home/End keyboard navigation.

## Livewire and development

Load `@denseScripts` before Livewire initializes Alpine, normally in the
document head. Registration is idempotent and does not attach a second Alpine
instance during Livewire morphing or navigation.

Build package assets from the repository root:

```bash
npm.cmd exec vite -- build --config packages/ui/dense/vite.config.js
```

Source files live in `resources/js` and `resources/css`. The reproducible
outputs are `dist/dense.js` and `dist/dense.css`.

To add a behavior: confirm it is reusable and domain-neutral, add one module
under `resources/js/components`, register it in `resources/js/index.js`,
connect it to a Blade component without cloning DOM, add accessibility and
tests, rebuild the assets, and verify Livewire morphing/navigation.

## Future component candidates

| Candidate | Evidence | Status |
| --- | --- | --- |
| Dropzone behavior | Existing reusable upload component uses local Alpine state | READY |
| Calendar/date picker normalization | Existing components contain duplicated date state | DEFERRED |
| Disclosure/card collapse | Card and table-card expose local open state | DEFERRED |
| Popover/positioning | Existing picker components position floating results | DEFERRED |
| Scanner input | Scanner workflows exist, but no package-neutral contract exists | DEFERRED |
| Table row selection | Server-owned selection semantics need a generic contract | DEFERRED |
| Domain selectors | Product, Store, Asset, and Container rules belong to applications | REJECTED-AS-DOMAIN-SPECIFIC |
