@extends('admin.panel')

@section('contents')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <form action="{{ isset($project) ? route('admin.software.update', ['project' => $project->id]) : route('admin.software.add') }}"
                    method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <input type="text" class="form-control-lg" name="name" placeholder="Project Name" style="width: 100%;" required
                        value="{{ old('name') ?? (isset($project) ? $project->name : '') }}"/>
                    @error('name')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div class="mb-3">
                    <select class="form-select" name="type" required>
                        <option selected disabled>Select Project Type</option>
                        @foreach(config('software.enum.type') as $type)
                            <option value="{{ $type }}" @if(old('type') == $type || (isset($project) && $project->type == $type)) selected @endif>
                                {{ ucwords($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Logo --}}
                <div class="mb-3">
                    <label class="fs-5" for="logo" accept="image/*" style="text-align: left; width: 100%;">Select Logo</label>
                    <input class="form-control" type="file" name="logo"/>
                    @error('logo')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Desc --}}
                <div class="mb-3">
                    <textarea class="form-control" name="desc"
                        placeholder="Project Description">{{ old('desc') ?? (isset($project) ? $project->desc : '') }}</textarea>
                    @error('desc')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Technologies --}}
                <div class="mb-3">
                    @foreach(config('software.technologies') as $id => $technology)
                        <input type="checkbox" class="btn-check" name="technology-{{ $id }}" id="technology-{{ $id }}" autocomplete="off"
                            @if(!is_null(old('technology-' . $id)) || (isset($project) && in_array($id, $project->technologies))) checked @endif>
                        <label class="btn btn-outline-{{ $technology['bg_color'] }} m-1" for="technology-{{ $id }}">{{ $technology['name'] }}</label>
                    @endforeach
                </div>

                {{-- Codebase link/private codebase --}}
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="private-codebase" type="checkbox" id="private-codebase-checkbox"
                            @if(!is_null(old('private-codebase')) || (isset($project) && is_null($project->codebase_link))) checked @endif>
                        <label class="form-check-label" for="private-codebase-checkbox" style="text-align: left; width: 100%;">Private Codebase</label>
                    </div>
                    <input type="text" class="form-control" name="codebase-link" id="codebase-link-input" placeholder="Codebase Link" style="width: 100%;"
                        value="{{ old('codebase-link') ?? (isset($project) ? $project->codebase_link : '') }}"
                        @if(!is_null(old('private-codebase')) || (isset($project) && is_null($project->codebase_link))) disabled @endif
                        />
                    @error('codebase-link')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Application link --}}
                <div class="mb-3">
                    <input type="text" class="form-control" name="app-link" placeholder="Application Link" style="width: 100%;" required
                        value="{{ old('app-link') ?? (isset($project) ? $project->app_link : '') }}"/>
                    @error('app-link')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-success mt-3" style="width: 100%;">@isset($project) Update @else Create @endisset</button>
            </form>
        </div>
    </div>
@endsection