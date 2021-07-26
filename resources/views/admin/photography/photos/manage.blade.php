@extends('admin.panel')

@section('contents')
    <div class="row">
        @foreach($shoot->photos as $photo)
            <div class="col-12 col-sm-6 col-xl-4 mb-3">
                {{-- Delete form --}}
                <form action="{{ route('admin.photography.photo.destroy', ['photo' => $photo->id]) }}" id="delete-photo-{{ $photo->id }}" method="POST">
                    @csrf
                </form>

                {{-- Update form --}}
                <div class="card" style="height: 100%;">
                    <form class="d-flex flex-column" style="height: 100%;"
                        action="{{ route('admin.photography.photo.update', ['photo' => $photo->id]) }}" method="POST">
                        @csrf
                        
                        <div class="card-header p-1 pb-2 d-flex">
                            {{-- Show on home checkbox --}}
                            <input type="checkbox" class="btn-check" name="show-on-home" id="show-on-home-{{ $photo->id }}" autocomplete="off"
                                @if($photo->show_on_home) checked @endif>
                            <label class="btn btn-outline-success mr-2" for="show-on-home-{{ $photo->id }}">
                                <img src="/storage/icons/home.png" />
                            </label>

                            {{-- Spacer --}}
                            <div style="min-width: 2.5%"></div>

                            {{-- Caption --}}
                            <input type="text" class="form-control" name="caption" value="{{ $photo->caption }}" required />
                        </div>

                        {{-- Preview image --}}
                        <div class="d-flex flex-column justify-content-center flex-grow-1">
                            <img src="/storage/images/photography/compressed/{{ $photo->asset }}" class="card-img-top mt-2 mb-1" />
                        </div>

                        {{-- Categories expand --}}
                        <div class="card-body collapse" id="category-collapse-{{ $photo->id }}">
                            @foreach(config('photography.categories') as $id => $category)
                                <input type="checkbox" class="btn-check" name="category-{{ $id }}" id="category-{{ $photo->id }}-{{ $id }}" autocomplete="off"
                                    @if(in_array($id, $photo->categories))) checked @endif>
                                <label class="btn btn-outline-{{ $category['bg_color'] }} m-1" for="category-{{ $photo->id }}-{{ $id }}">{{ $category['name'] }}</label>
                            @endforeach
                        </div>

                        <ul class="list-group list-group-flush mt-1 border-top">
                            <li class="list-group-item btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#category-collapse-{{ $photo->id }}">
                                Categories
                            </li>
                        </ul>
                        
                        <div class="card-footer d-flex">
                            {{-- Delete button --}}
                            <button type="button" class="btn btn-danger"
                                onclick="verifyDeleteForm('Are you sure you want to delete this photo?', '#delete-photo-{{ $photo->id }}')">
                                <img src="/storage/icons/delete.png" />
                            </button>

                            {{-- Spacer --}}
                            <div style="min-width: 5%"></div>

                            {{-- Save button --}}
                            <button type="submit" class="btn btn-success flex-grow-1">
                                <img src="/storage/icons/save.png" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection