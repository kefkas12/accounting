@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
    <div class="card" style="border-radius: 0">
        <div class="card-header">Undang pengguna</div>
        <div class="card-body">
            <h3>Info Pengguna</h3>
            <form @if(isset($is_edit)) action="{{ url('pengaturan/pengguna/edit').'/'.$user->id }}" @else action="{{ url('pengaturan/pengguna/insert') }}" @endif method="POST" style="font-size: 12px" id="insertForm">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="name">Nama Pengguna</label>
                        <input type="text" class="form-control" name="name" id="name" @if(isset($is_edit)) value="{{ $user->name }}" readonly @endif required>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" @if(isset($is_edit)) value="{{ $user->email }}" readonly @endif required>
                    </div>
                    @if(!isset($is_edit))
                    <div class="form-group col-md-5">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" required>
                    </div>
                    @endif
                    <div class="form-group col-md-5">
                        <label for="confirm_password">Role</label><br>
                        @foreach($role as $v)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="role[]" id="role_{{ $loop->index+1 }}" value="{{ $v->name }}" @if(isset($is_edit)) @if (in_array($v->name, $my_role)) checked @endif @endif>
                            <label class="form-check-label" for="role_{{ $loop->index+1 }}">{{ $v->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group col-md-10 d-flex justify-content-end">
                        <a href="{{ url('pengaturan/pengguna') }}" class="btn btn-light">Batalkan</a>
                        @if(isset($is_edit))
                        <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                        @else
                        <button type="button" class="btn btn-primary" onclick="check_password()">Undang</button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script>
        function check_password() {
            event.preventDefault();
            if (!$('#name').val() || !$('#email').val()) {
                Swal.fire({
                    title: 'Name and email didn`t match',
                text: 'Nama dan email harus harus di isi',
                icon: 'error'
            })
        } else {
            if ($('#password').val()) {
                if ($('#password').val() != $('#confirm_password').val()) {
                    Swal.fire({
                        title: 'Password didn`t match',
                            text: 'Password harus sama dengan Confirm Password.',
                            icon: 'error'
                        })
                    } else {
                        $('#insertForm').submit();
                    }
                } else {
                    Swal.fire({
                        title: 'Password didn`t match',
                        text: 'Password dan confirm harus harus di isi',
                        icon: 'error'
                    })
                }
            }
            // 
        }
    </script>
@endsection
