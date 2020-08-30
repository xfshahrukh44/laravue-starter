<template>
    <div class="container">

        <!-- Index view -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h3 class="card-title col-md-11">Users</h3>
                            <button class="btn btn-success xs col-md-1" id="add_program" @click="addUserModal">
                                <i class="fas fa-user-plus fa-lg"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <!-- <div class="row">
                                <div class="col-sm-12 col-md-6"></div>
                                <div class="col-sm-12 col-md-6"></div>
                            </div> -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="indexTable" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" rowspan="1" colspan="1" aria-sort="ascending" >Name</th>
                                                <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Email</th>
                                                <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Phone(s)</th>
                                                <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Type</th>
                                                <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Registered at</th>
                                                <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr role="row" class="odd" v-if="users.length > 0" v-for="user in users">
                                                <td tabindex="0" class="sorting_1">{{user.name}}</td>
                                                <td>{{user.email}}</td>
                                                <td>{{user.phone}}</td>
                                                <td>{{user.type | upText}}</td>
                                                <td>{{user.created_at | myDate}}</td>
                                                <td>
                                                    <!-- View -->
                                                    <a href="#">
                                                        <i class="fas fa-eye blue ml-1"></i>
                                                    </a>
                                                    |
                                                    <!-- Edit -->
                                                    <a href="#">
                                                        <i class="fas fa-edit blue ml-1" @click="editUserModal(user)"></i>
                                                    </a>
                                                    |
                                                    <!-- Delete -->
                                                    <a href="#" @click="deleteUser(user.id)">
                                                        <i class="fas fa-trash red ml-1"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>

                                        <tfoot>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
                                </div> -->
                                <div class="col-sm-12 col-md-12">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item previous disabled" id="example2_previous">
                                                <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                            </li>
                                            <li class="paginate_button page-item active">
                                                <a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                                            </li>
                                            <li class="paginate_button page-item ">
                                                <a href="#" aria-controls="example2" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                                            </li>
                                            <li class="paginate_button page-item ">
                                                <a href="#" aria-controls="example2" data-dt-idx="3" tabindex="0" class="page-link">3</a>
                                            </li>
                                            <li class="paginate_button page-item ">
                                                <a href="#" aria-controls="example2" data-dt-idx="4" tabindex="0" class="page-link">4</a>
                                            </li>
                                            <li class="paginate_button page-item ">
                                                <a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0" class="page-link">5</a>
                                            </li>
                                            <li class="paginate_button page-item ">
                                                <a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0" class="page-link">6</a>
                                            </li>
                                            <li class="paginate_button page-item next" id="example2_next">
                                                <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>

        <!-- Create/Edit view -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 v-show="!editmode" class="modal-title" id="addUserModalLabel">Add New User</h5>
                <h5 v-show="editmode" class="modal-title" id="addUserModalLabel">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateUser() : createUser()">
                <div class="modal-body">
                    <!-- name -->
                    <div class="form-group">
                        <input v-model="form.name" id="name" type="text" name="name" placeholder="Enter name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required max="50">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <!-- email -->
                    <div class="form-group">
                        <input v-model="form.email" id="email" type="email" name="email" placeholder="Enter email"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" required max="50">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <!-- password -->
                    <div class="form-group">
                        <input v-model="form.password" id="password" type="password" name="password" placeholder="Enter password"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" min=8>
                        <has-error :form="form" field="password"></has-error>
                    </div>
                    <!-- phone -->
                    <div class="form-group">
                        <input v-model="form.phone" id="phone" type="text" name="phone" placeholder="Enter phone"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }" required>
                        <has-error :form="form" field="phone"></has-error>
                    </div>
                    <!-- type -->
                    <div class="form-group">
                    <select v-model="form.type" id="type" name="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }" required>
                        <option value="">Select Section</option>
                        <!-- <option v-for="section in sections" :value="section.id">{{section.classroom.title + ' - ' + section.title}}</option> -->
                        <option value="user">User</option>
                    </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    <button v-show="editmode" type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                editmode: '',
                users:{},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    phone: '',
                    type: '',
                }),
            }
        },
        methods: {
            // Create
            addUserModal(){
                this.form.reset();
                this.editmode = false;
                $('#addUserModal').modal('show');
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then(() => {
                    this.loadUsers();
                    $('#addUserModal').modal('hide');
                    this.$Progress.finish();
                })
                .then(() => {
                    toast.fire({
                        icon: 'success',
                        title: 'User created successfully'
                        })
                })
                .catch(()=>{this.$Progress.fail();});
            },
            // Read
            loadUsers(){
                axios.get('api/user')
                .then(({data})=>(this.users = data.data))
                .catch(()=>{this.$Progress.fail();});
            },
            // Update
            editUserModal(user){
                this.form.fill(user);
                this.editmode = true;
                $('#addUserModal').modal('show');
            },
            updateUser(){
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
                .then(()=>{
                    this.loadUsers();
                    $('#addUserModal').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'User Updated Successfully'
                    });
                    this.$Progress.finish();
                })
                .catch(()=>{this.$Progress.fail();});
            },
            // Delete
            deleteUser(user_id){
                swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if(result.value){
                        this.$Progress.start();
                        this.form.delete('api/user/' + user_id)
                        .then((result) => {
                            swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.loadUsers();
                            this.$Progress.finish();
                        }).catch(() => {this.$Progress.fail();});
                    }
                }).catch(() => {this.$Progress.fail();});
            }
        },
        mounted() {
            this.loadUsers();
            console.log('Component mounted.');
        }
    }
</script>