@props(['searchFields' => []])

<!-- Search Form -->
<div class="card-body">
    <div class="collapse" id="collapseSearch">
        <h5 class="card-action-title mb-0">Pencarian Lanjutan</h5>
        <div class="row">
            <div class="col-12">
                <div class="row g-3">
                    @foreach ($searchFields as $key => $item)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label">{{ $item['name'] }}:</label>
                            <input
                                type="text"
                                class="form-control dt-input dt-full-name"
                                column-name="{{$key}}" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Search Form -->
