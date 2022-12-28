@extends('admin.panel')

@section('contents')
    <div class="row justify-content-center mt-4">
        <div class="col-12 col-md-8">


            {{-- Copy --}}
            @isset($shoot->pear_slug)
            <form>
                <div class="input-group">
                    <input type="text" class="form-control" value="{{ 'www.portraitpear.photography/' . $shoot->pear_slug }}" id="copy-input" readonly>
                    <span class="input-group-btn">
                    <button class="btn btn-info" type="button" id="copy-button"
                        data-toggle="tooltip" data-placement="button"
                        title="Copy to Clipboard">
                        Copy
                    </button>
                    </span>
                </div>
            </form>              
            @else
                <h3 class="display-6 text-center mt-3">No slug generated ¯\_(ツ)_/¯</h3>
            @endisset

            <form id="pear-shoot-form" action="{{ route('admin.photography.shooot.toggle', ['shoot' => $shoot->id]) }}" method="POST">
                @csrf

                <button type="submit" class="btn {{ is_null($shoot->pear_slug) ? 'btn-success' : 'btn-danger' }} mt-5" style="width: 100%;"
                    onclick="event.preventDefault(); verifyDeleteForm('Are you sure you want to toggle this shoot\'s Pear slug??', '#pear-shoot-form', 'Re-roll?')">
                    {{ is_null($shoot->pear_slug) ? 'Generate Slug' : 'Remove Slug' }}
                </button>
            </form>
        </div>
    </div>
@endsection