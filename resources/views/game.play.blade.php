{{-- Debug des données --}}
@if($countries->isEmpty())
  <div class="alert alert-danger">Aucun pays chargé !</div>
@else
  <div hidden>
    @foreach($countries as $c)
      <!-- {{ $c->name }} -->
    @endforeach
  </div>
@endif