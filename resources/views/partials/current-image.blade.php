@if($image_url)
<div class="mb-3">
    <label class="form-label">{{ $title ?? 'Current Image' }}</label>
    <div class="d-flex align-items-center gap-3">
        <img src="{{ $image_url }}" 
             alt="Current image" 
             style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 8px; border: 1px solid #dee2e6;">
        <div class="small text-muted">
            <div><strong>Path:</strong> {{ $image_path }}</div>
            <div><strong>URL:</strong> <a href="{{ $image_url }}" target="_blank">{{ $image_url }}</a></div>
        </div>
    </div>
</div>
@endif
