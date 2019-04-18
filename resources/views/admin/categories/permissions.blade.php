@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ route('categoryUpdatePermissions', $category) }}">
                    @csrf

                    <h2 class="h2">Category: <strong>{{$category->name}}</strong></h2>

                    <div class="row mt-4 mb-4">
                        <div class="col-6">
                            <h5 class="h5 mb-1">Users to remove from the category:</h5>
                            <ul class="list-group mt-3" id="user-list">
                                @foreach($category->permittedUsers as $user)
                                    <li class="list-group-item" data-user-id="{{$user->id}}">
                                        <span class="col-12">{{$user->name}}</span>
                                        <span>
                                            <button class="btn btn-danger"
                                                    onclick="window.removeUser(event)"
                                                    type="button">REMOVE</button>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6">
                            <h5 class="h5 mb-1">Users to assign the category to:</h5>
                            <ul class="list-group" id="add-list">
                                @foreach($users as $user)
                                    <li class="list-group-item" data-user-id="{{$user->id}}">
                                        <span class="col-12">{{$user->name}}</span>
                                        <span>
                                            <button class="btn btn-primary"
                                                    onclick="window.addUser(event)"
                                                    type="button">ADD</button>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
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

        const createBtn = (text, classes = [], onClick) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = text;
            btn.classList.add(...classes);
            btn.onclick = onClick;
            return btn;
        };
        const addBtn = () => createBtn('ADD', ['btn', 'btn-primary'], ev => window.addUser(ev));
        const removeBtn = () => createBtn('REMOVE', ['btn', 'btn-danger'], ev => window.removeUser(ev));
        const resetBtn = () => createBtn('RESET', ['btn', 'btn-outline-primary'], ev => window.resetUser(ev));

        const createHiddenInput = (name, value) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            return input;
        };

        const findClosestParent = (el, tagName) => {
            let list = el.parentNode;
            while (list.tagName !== tagName.toUpperCase() && list.tagName !== 'HTML') {
                list = list.parentNode;
            }
            return list.tagName === tagName.toUpperCase() ? list : null;
        };

        window.removeUser = ev => {
            const listItem = findClosestParent(ev.target, 'li');
            const currentBtn = listItem.querySelector('button');
            currentBtn.parentNode.appendChild(resetBtn());
            currentBtn.remove();
            listItem.appendChild(
                createHiddenInput('idsToDetach[]', listItem.getAttribute('data-user-id'))
            );
        };

        window.addUser = ev => {
            const listItem = findClosestParent(ev.target, 'li');
            const currentBtn = listItem.querySelector('button');
            currentBtn.parentNode.appendChild(resetBtn());
            currentBtn.remove();
            listItem.appendChild(
                createHiddenInput('idsToAttach[]', listItem.getAttribute('data-user-id'))
            );
        };

        window.resetUser = ev => {
            const listItem = findClosestParent(ev.target, 'li');
            const input = listItem.querySelector('input');
            if (input) {
                input.remove();
            }
            const currentBtn = listItem.querySelector('button');
            const ul = findClosestParent(ev.target, 'ul');
            switch (ul.id) {
                case 'user-list':
                    currentBtn.parentNode.appendChild(removeBtn());
                    break;
                case 'add-list':
                    currentBtn.parentNode.appendChild(addBtn());
                    break;
            }
            currentBtn.remove();
        };

        // const UserListItem = (user, dest) => {
        //     const componentString = `
        //         <span class="col-12">${user.name}</span>
        //         <span>
        //             <button class="btn btn-outline-primary"
        //                     onclick="window.removeUser(event, '${dest}')"
        //                     data-user='${JSON.stringify(user)}'
        //                     type="button">REMOVE</button>
        //         </span>
        //     `;
        //     const li = document.createElement('li');
        //     li.classList.add('list-group-item');
        //     li.innerHTML = componentString;
        //     return li;
        // };
        //
        // const AddListItem = user => {
        //     const componentString = `
        //         <span class="col-12">${user.name}</span>
        //         <span>
        //             <button class="btn btn-outline-primary"
        //                     onclick="window.addUser(event, 'to-add)"
        //                     data-user='${JSON.stringify(user)}'
        //                     type="button">ADD</button>
        //         </span>
        //     `;
        //     const li = document.createElement('li');
        //     li.classList.add('list-group-item');
        //     li.innerHTML = componentString;
        //     return li;
        // };
        //
        // const RemoveListItem = user => {
        //     const componentString = `
        //         <span class="col-12">${user.name}</span>
        //         <span>
        //             <button class="btn btn-outline-primary"
        //                     onclick="window.addUser(event, 'to-remove')"
        //                     data-user='${JSON.stringify(user)}'
        //                     type="button">ADD</button>
        //         </span>
        //     `;
        //     const li = document.createElement('li');
        //     li.classList.add('list-group-item');
        //     li.innerHTML = componentString;
        //     return li;
        // };
    </script>
@endsection
