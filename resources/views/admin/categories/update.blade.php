@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ route('categoryUpdate', $category) }}">
                    <input name="_method" type="hidden" value="PUT">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                   name="name" value="{{ $category->name }}" required autofocus/>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="col-md-4"></div>
                        <div class="col-md-6">
                            <h5 class="h5 d-block mt-4">Custom fields</h5>
                        </div>
                    </div>

                    <div class="mb-4">
                        @foreach($category->customFields as $field)
                            <div class="form-group row field-item">
                                <div class="_id d-none">{{$field->id}}</div>
                                <div class="col-md-4 col-form-label text-md-right"></div>
                                <div class="col-md-6 d-flex">
                                    <input aria-label="{{ $field->name }}"
                                           class="form-control {{ $errors->has("customFields[{$field->id}][name]") ? ' is-invalid' : '' }}"
                                           name="customFields[{{$field->id}}][name]" value="{{ $field->name }}"
                                           required/>
                                    <button type="button" class="delete-btn btn btn-danger btn-sm ml-1"
                                            onclick="toggleDelete(this)">DELETE
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-4 new-fields">
                        <div class="d-flex">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <button type="button" onclick="addField()" class="mb-3 btn btn-outline-primary btn-sm">
                                    ADD A FIELD
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const findClosestParentWithClassName = (el, className) => {
            let list = el.parentNode;
            while (!list.classList.contains(className) && list.tagName !== 'HTML') {
                list = list.parentNode;
            }
            return list.classList.contains(className) ? list : null;
        };

        const createInput = (type, name, value, classes = []) => {
            const input = document.createElement('input');
            input.type = type;
            input.name = name;
            input.value = value;
            input.classList.add(...classes);
            return input;
        };

        const addField = () => {
            const div = document.createElement('div');
            div.classList.add('form-group', 'row', 'new-field');
            div.innerHTML = `
                <div class="col-md-4 col-form-label text-md-right"></div>
                <div class="col-md-6">
                    <input class="form-control" name="newFields[]" required/>
                </div>
            `;
            document.querySelector('.new-fields').appendChild(div);
        };

        const toggleDelete = el => {
            const fieldItem = findClosestParentWithClassName(el, 'field-item');
            const deleteButton = fieldItem.querySelector('.delete-btn');
            const input = fieldItem.querySelector('input');
            if (deleteButton.textContent.trim() === 'DELETE') {
                input.disabled = true;
                input.setAttribute('old-name', input.name);
                input.removeAttribute('name');
                deleteButton.textContent = 'UNDO';
                fieldItem.appendChild(
                    createInput(
                        'hidden',
                        'fieldsToDelete[]',
                        +fieldItem.querySelector('._id').textContent,
                        ['to-delete-id']
                    )
                );
            } else {
                input.disabled = false;
                input.name = input.getAttribute('old-name');
                deleteButton.textContent = 'DELETE';
                fieldItem.querySelector('.to-delete-id').remove();
            }
        };
    </script>
@endsection
