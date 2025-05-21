@php
$containerFooter = !empty($containerNav) ? $containerNav : 'container-fluid';
@endphp

<div class="modal fade" id="gantiAksesModal" tabindex="-1" aria-labelledby="gantiAksesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('gantihakakses') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gantiAksesLabel">Ganti Hak Akses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="akses" class="form-label">Pilih Hak Akses</label>
                        <select class="form-select" id="id_akses" name="id_akses">
                            @foreach(Auth()->user()->aksesuser as $akses)
                                <option value="{{ $akses->id_akses }}" <?= session('akses_default_id') == $akses->id_akses? 'selected':''; ?>>{{ $akses->akses->nama }}</option>
                            @endforeach
                        </select>
                        <!-- Konten form / info ganti akses di sini -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Ganti</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="text-body">
        Copyright © <script>document.write(new Date().getFullYear())</script>, <a href="{{ (!empty(config('variables.creatorUrl')) ? config('variables.creatorUrl') : '') }}" target="_blank" class="footer-link">{{ (!empty(config('variables.creatorName')) ? config('variables.creatorName') : '') }}</a>
      </div>
    </div>
  </div>
</footer>
<!--/ Footer-->
