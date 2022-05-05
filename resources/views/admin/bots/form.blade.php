@extends('admin.panel')

@section('contents')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <form action="{{ isset($bot) ? route('admin.bots.update', ['bot' => $bot->id]) : route('admin.bots.add') }}"
                    method="POST" enctype="multipart/form-data" class="mb-3">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <input type="text" class="form-control-lg" name="name" placeholder="Bot Name" style="width: 100%;" required
                        value="{{ old('name') ?? (isset($bot) ? $bot->name : '') }}"/>
                    @error('name')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Img --}}
                <div class="mb-3">
                    <label class="fs-5" for="img" style="text-align: left; width: 100%;">Select Image</label>
                    <input class="form-control" type="file" name="img" accept="image/*"/>
                    @error('img')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Desc --}}
                <div class="mb-3">
                    <textarea class="form-control" name="desc"
                        placeholder="Bot Description">{{ old('desc') ?? (isset($bot) ? $bot->desc : '') }}</textarea>
                    @error('desc')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Client ID --}}
                <div class="mb-3">
                    <label class="fs-6" for="client-id" style="text-align: left; width: 100%;">Application ID in Discord Developer Portal</label>
                    <input type="text" class="form-control-lg" name="client-id" placeholder="Client ID" style="width: 100%;" required
                        value="{{ old('client-id') ?? (isset($bot) ? $bot->client_id : '') }}"/>
                    @error('client-id')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Permissons --}}
                <div class="mb-3">
                    <input type="text" class="form-control-lg" name="permissions" placeholder="Permissions" style="width: 100%;"
                        value="{{ old('permissions') ?? (isset($bot) ? $bot->permissions : '') }}"/>
                    @error('permissions')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Scope --}}
                <div class="mb-3">
                    <label class="fs-6" for="scope" style="text-align: left; width: 100%;">Separate multiple scopes with spaces</label>
                    <input type="text" class="form-control-lg" name="scope" placeholder="Scope" style="width: 100%;" required
                        value="{{ old('scope') ?? (isset($bot) ? $bot->scope : '') }}"/>
                    @error('scope')
                        <p class="text-danger fs-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-success mt-1" style="width: 100%;">@isset($bot) Update @else Create @endisset</button>
            </form>

            {{-- Delete --}}
            @isset($bot)
                <form id="delete-bot-form" action="{{ route('admin.bots.destroy', ['bot' => $bot->id]) }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-danger mt-3" style="width: 100%;"
                        onclick="event.preventDefault(); verifyDeleteForm('Are you sure you want to delete this software bot?', '#delete-bot-form')">
                        Delete
                    </button>
                </form>
            @endisset
        </div>
    </div>
@endsection