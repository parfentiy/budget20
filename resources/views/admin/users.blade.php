<x-header/>
<x-app-layout/>

<div class="d-flex flex-column mx-2 my-2 align-items-center">
    <div class="d-flex flex-row text-center fw-bold">
            Список пользователей
    </div>
    <div>
        <table class="table table-sm table-bordered table-striped table-hover align-top">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Заблокирован</th>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $user)
                <tr>
                    <td class="text-center">
                        {{$user->id}}
                    </td>
                    <td>
                        {{$user->name}}
                    </td>
                    <td class="text-left">
                        {{$user->email}}
                    </td>
                    <form method="post" enctype="multipart/form-data" action="{{route('changeUserBan')}}">
                        @csrf
                    <td class="col-1 align-top">
                        <button type="submit" class="btn btn-primary btn-sm" name="userId" value="{{$user->id}}">
                            @if($user->banned_till === '0')
                                Разблокировать
                            @else
                                Заблокировать
                            @endif
                        </button>
                    </td>
                    </form>
                    <td>
                        <form method="post" enctype="multipart/form-data" action="{{route('user.delete')}}">
                        @csrf
                            <button type="submit" class="btn btn-danger btn-sm" name="userId" value="{{$user->id}}" onclick="return confirm('Уверены, что хотите удалить?');">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
