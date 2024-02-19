@extends('layouts.app')

@section('content')
@include('layouts.headers.cards')
<!-- Page content -->
<div class="container-fluid mt--6">
    <!-- Dark table -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" hidden>
                        Tambah Role
                    </button>
                </div>
                <form action="{{ url('permission').'/'.$role->id }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <h5 class="col pl-0 pr-2">Access for {{ $role->name }}</h5>

                            <!-- <a class="btn btn-light bg-transparent border-0 text-danger m-1" href="">
                            <h1 class="material-icons">close</h1>
                        </a>
                        -->
                            <button class="btn btn-light bg-transparent border-0 m-1" type="submit" name="submit">
                            <i class="fa fa-paper-plane text-primary"></i>

                            </button>
                        </div>
                        <div class="d-flex flex-row-reverse">
                            <div class="row">
                                <div class="p-2">
                                    <input type="checkbox" id="check_all">
                                </div>
                                <div class="p-2">
                                    <label class="form-check-label" for="view">
                                        Check All
                                    </label>
                                </div>
                            </div>
                        </div>
                        <table class="table" style="width:100%" id="table_akses">
                            <thead>
                                <tr>
                                    <th rowspan="2">Data</th>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <th>Setting</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Admin</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_role" id="read_role">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_role" id="create_role">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_role" id="update_role">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check" >
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="permission_role" id="permission_role">
                                            <label class="form-check-label" for="delete"> 
                                                Permission
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_setting" id="read_setting">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_setting" id="create_setting">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_setting" id="update_setting">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_setting" id="delete_setting">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <div class="table-responsive">
                        <table class="table" style="width:100%" id="table_akses">
                            <thead>
                                <tr>
                                    <th rowspan="2">Data</th>
                                    <th rowspan="2">Menu</th>
                                    <th colspan="6" class="text-center">Kubota</th>
                                </tr>
                                <tr>
                                    <th>Palembang</th>
                                    <th>Lampung</th>
                                    <th>Bengkulu</th>
                                    <th>NTT</th>
                                    <th>NTB</th>
                                    <th>jambi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>User</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="import_user" id="import_user">
                                            <label class="form-check-label" for="import">
                                                Import
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_user_palembang" id="read_user_palembang">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_user_palembang" id="create_user_palembang">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_user_palembang" id="update_user_palembang">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check" hidden>
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_user_palembang" id="delete_user_palembang">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_user_lampung" id="read_user_lampung">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_user_lampung" id="create_user_lampung">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_user_lampung" id="update_user_lampung">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check" hidden>
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_user_lampung" id="delete_user_lampung">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_user_bengkulu" id="read_user_bengkulu">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_user_bengkulu" id="create_user_bengkulu">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_user_bengkulu" id="update_user_bengkulu">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check" hidden>
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_user_bengkulu" id="delete_user_bengkulu">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_user_ntt" id="read_user_ntt">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_user_ntt" id="create_user_ntt">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_user_ntt" id="update_user_ntt">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check" hidden>
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_user_ntt" id="delete_user_ntt">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_user_ntb" id="read_user_ntb">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_user_ntb" id="create_user_ntb">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_user_ntb" id="update_user_ntb">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check" hidden>
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_user_ntb" id="delete_user_ntb">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_user_jambi" id="read_user_jambi">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_user_jambi" id="create_user_jambi">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_user_jambi" id="update_user_jambi">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check" hidden>
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_user_jambi" id="delete_user_jambi">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Receive Item</td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_receive_item_palembang" id="read_receive_item_palembang">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_receive_item_palembang" id="create_receive_item_palembang">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_receive_item_palembang" id="update_receive_item_palembang">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_receive_item_palembang" id="delete_receive_item_palembang">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_receive_item_lampung" id="read_receive_item_lampung">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_receive_item_lampung" id="create_receive_item_lampung">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_receive_item_lampung" id="update_receive_item_lampung">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_receive_item_lampung" id="delete_receive_item_lampung">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_receive_item_bengkulu" id="read_receive_item_bengkulu">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_receive_item_bengkulu" id="create_receive_item_bengkulu">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_receive_item_bengkulu" id="update_receive_item_bengkulu">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_receive_item_bengkulu" id="delete_receive_item_bengkulu">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_receive_item_ntt" id="read_receive_item_ntt">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_receive_item_ntt" id="create_receive_item_ntt">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_receive_item_ntt" id="update_receive_item_ntt">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_receive_item_ntt" id="delete_receive_item_ntt">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_receive_item_ntb" id="read_receive_item_ntb">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_receive_item_ntb" id="create_receive_item_ntb">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_receive_item_ntb" id="update_receive_item_ntb">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_receive_item_ntb" id="delete_receive_item_ntb">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_receive_item_jambi" id="read_receive_item_jambi">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_receive_item_jambi" id="create_receive_item_jambi">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_receive_item_jambi" id="update_receive_item_jambi">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_receive_item_jambi" id="delete_receive_item_jambi">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Unit</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="import_unit" id="import_unit">
                                            <label class="form-check-label" for="view">
                                                Import
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="mutasi" id="mutasi">
                                            <label class="form-check-label" for="mutasi">
                                                Mutasi
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_unit_palembang" id="read_unit_palembang">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_unit_palembang" id="create_unit_palembang">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_unit_palembang" id="update_unit_palembang">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_unit_palembang" id="delete_unit_palembang">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_unit_palembang" id="export_unit_palembang">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_unit_lampung" id="read_unit_lampung">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_unit_lampung" id="create_unit_lampung">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_unit_lampung" id="update_unit_lampung">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_unit_lampung" id="delete_unit_lampung">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_unit_lampung" id="export_unit_lampung">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_unit_bengkulu" id="read_unit_bengkulu">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_unit_bengkulu" id="create_unit_bengkulu">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_unit_bengkulu" id="update_unit_bengkulu">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_unit_bengkulu" id="delete_unit_bengkulu">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_unit_bengkulu" id="export_unit_bengkulu">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_unit_ntt" id="read_unit_ntt">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_unit_ntt" id="create_unit_ntt">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_unit_ntt" id="update_unit_ntt">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_unit_ntt" id="delete_unit_ntt">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_unit_ntt" id="export_unit_ntt">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_unit_ntb" id="read_unit_ntb">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_unit_ntb" id="create_unit_ntb">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_unit_ntb" id="update_unit_ntb">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_unit_ntb" id="delete_unit_ntb">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_unit_ntb" id="export_unit_ntb">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_unit_jambi" id="read_unit_jambi">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_unit_jambi" id="create_unit_jambi">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_unit_jambi" id="update_unit_jambi">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_unit_jambi" id="delete_unit_jambi">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_unit_jambi" id="export_unit_jambi">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>Job Request</td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_job_request_palembang" id="read_job_request_palembang">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_job_request_palembang" id="create_job_request_palembang">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_job_request_palembang" id="update_job_request_palembang">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_job_request_palembang" id="delete_job_request_palembang">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_job_request_lampung" id="read_job_request_lampung">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_job_request_lampung" id="create_job_request_lampung">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_job_request_lampung" id="update_job_request_lampung">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_job_request_lampung" id="delete_job_request_lampung">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_job_request_bengkulu" id="read_job_request_bengkulu">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_job_request_bengkulu" id="create_job_request_bengkulu">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_job_request_bengkulu" id="update_job_request_bengkulu">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_job_request_bengkulu" id="delete_job_request_bengkulu">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_job_request_ntt" id="read_job_request_ntt">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_job_request_ntt" id="create_job_request_ntt">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_job_request_ntt" id="update_job_request_ntt">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_job_request_ntt" id="delete_job_request_ntt">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_job_request_ntb" id="read_job_request_ntb">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_job_request_ntb" id="create_job_request_ntb">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_job_request_ntb" id="update_job_request_ntb">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_job_request_ntb" id="delete_job_request_ntb">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_job_request_jambi" id="read_job_request_jambi">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_job_request_jambi" id="create_job_request_jambi">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_job_request_jambi" id="update_job_request_jambi">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_job_request_jambi" id="delete_job_request_jambi">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Service</td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_service_palembang" id="read_service_palembang">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_service_palembang" id="create_service_palembang">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_service_palembang" id="update_service_palembang">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_service_palembang" id="delete_service_palembang">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_service_palembang" id="export_service_palembang">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_service_lampung" id="read_service_lampung">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_service_lampung" id="create_service_lampung">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_service_lampung" id="update_service_lampung">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_service_lampung" id="delete_service_lampung">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_service_lampung" id="export_service_lampung">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_service_bengkulu" id="read_service_bengkulu">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_service_bengkulu" id="create_service_bengkulu">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_service_bengkulu" id="update_service_bengkulu">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_service_bengkulu" id="delete_service_bengkulu">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_service_bengkulu" id="export_service_bengkulu">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_service_ntt" id="read_service_ntt">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_service_ntt" id="create_service_ntt">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_service_ntt" id="update_service_ntt">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_service_ntt" id="delete_service_ntt">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_service_ntt" id="export_service_ntt">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_service_ntb" id="read_service_ntb">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_service_ntb" id="create_service_ntb">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_service_ntb" id="update_service_ntb">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_service_ntb" id="delete_service_ntb">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_service_ntb" id="export_service_ntb">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="read_service_jambi" id="read_service_jambi">
                                            <label class="form-check-label" for="view">
                                                View
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="create_service_jambi" id="create_service_jambi">
                                            <label class="form-check-label" for="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="update_service_jambi" id="update_service_jambi">
                                            <label class="form-check-label" for="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="delete_service_jambi" id="delete_service_jambi">
                                            <label class="form-check-label" for="delete">
                                                Delete
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nama_akses[]" value="export_service_jambi" id="export_service_jambi">
                                            <label class="form-check-label" for="delete">
                                                Export
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    <?php foreach ($permission as $v) {  
    ?>
        $("#<?php echo $v->name; ?>").prop("checked", true);
    <?php } ?>
    $("#check_all").on('change', function() {
        if ($("#check_all").is(':checked')) {
            $('.form-check-input').prop("checked", true);
        } else {
            $('.form-check-input').prop("checked", false);
        }
    });
</script>
@endsection