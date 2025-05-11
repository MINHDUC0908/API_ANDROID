@extends('admin.layouts.app')
@section('content')
    <div>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Image List</h1>
            @foreach ($images as $item)
                <div>
                    {{
                        $item->image_path
                    }}
                </div>
            @endforeach
        </div>
    </div>
@endsection
