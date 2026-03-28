

<div class="tw-mt-auto">
  <div class="tw-mb-4 tw-ms-8 -tw-mt-1 no-print">
    <p class="tw-text-xs tw-font-normal tw-text-gray-500">
      {{ config('app.name', 'NAVIPOS') }} - <span class="tw-font-mono tw-font-medium"> V{{config('author.app_version')}}</span> | Copyright &copy; {{ date('Y') }} All rights reserved.
    </p>
  </div>
</div>

@if (auth()->check() && app('App\\Utils\\Util')->is_cashier(auth()->user()))
  <button id="mask-toggle" type="button" aria-label="Toggle mask"
    style="position:fixed;bottom:10px;right:10px;min-width:28px;height:24px;padding:0 6px;border-radius:12px;opacity:0.35;z-index:9999;border:1px solid #000;background:#000;color:#fff;font-size:11px;line-height:22px;cursor:pointer;">
    {{ \Carbon\Carbon::now()->format('Y-m-d') }}
  </button>
@endif
