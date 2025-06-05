@props(['searchFields' => []])

<!-- Search Form -->
<div class="card-body">
    <div class="collapse" id="collapseSearch">
        <h5 class="card-action-title mb-0">Pencarian Lanjutan</h5>
        <div class="row mt-5">
            <div class="col-12">
                <div class="row g-3">
                    <x-form.form-builder-search
                        :input="$searchFields" :fieldWidth="4" />
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Search Form -->
