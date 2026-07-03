@props(['status'])
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $status->badgeColor() }}">
    <span class="badge-dot {{ $status->dotColor() }}"></span>
    {{ $status->label() }}
</span>
