<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de Usuários</h3>

                <div class="card-tools">

                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Novo
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>msisdn</th>
                      <th>access_level</th>
                      <th>score</th>
                      <th>level</th>
                      <th>Ação</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="usuario in usuarios.data" :key="usuario.id">

                      <td>{{usuario.id}}</td>
                      <td>{{usuario.name}}</td>
                      <td>{{usuario.msisdn}}</td>
                      <td>{{usuario.access_level}}</td>
                      <td>{{usuario.score}}</td>
                      <td>{{usuario.level}}</td>
                      <!-- <td><img v-bind:src="'/' + usuario.photo" width="100" alt="usuario"></td> -->
                      <td>
                          <a href="#" @click="upgradeUsuario(usuario.id)">
                              <i class="fa fa-arrow-up  blue"></i>
                          </a>
                          /
                          <a href="#" @click="downgradeUsuario(usuario.id)">
                              <i class="fa fa-arrow-down  red"></i>
                          </a>
                          /
                        <a href="#" @click="editModal(usuario)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteUsuario(usuario.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="usuarios" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Novo Usuário</h5>
                    <h5 class="modal-title" v-show="editmode">Editar Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateUsuario() : createUsuario()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>service_id</label>
                            <input v-model="form.service_id" type="text" name="service_id"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('service_id') }">
                            <has-error :form="form" field="service_id"></has-error>
                        </div>
                        <div class="form-group">
                            <label>msisdn</label>
                            <input v-model="form.msisdn" type="text" name="msisdn"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('msisdn') }">
                            <has-error :form="form" field="msisdn"></has-error>
                        </div>
                        <div class="form-group">
                            <label>access_level</label>
                            <input v-model="form.access_level" type="text" name="access_level"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('access_level') }">
                            <has-error :form="form" field="access_level"></has-error>
                        </div>
                        <div class="form-group">
                            <label>score</label>
                            <input v-model="form.score" type="text" name="score"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('score') }">
                            <has-error :form="form" field="score"></has-error>
                        </div>
                        <div class="form-group">
                            <label>level</label>
                            <input v-model="form.level" type="text" name="level"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('level') }">
                            <has-error :form="form" field="level"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Editar</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Criar</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
      components: {
          VueTagsInput,
        },
        data () {
            return {
                editmode: false,
                usuarios : {},
                form: new Form({
                    id : '',
                    service_id : '',
                    msisdn: '',
                    access_level: '',
                    name: '',
                    score: '',
                    level: '',

                })
            }
        },
        methods: {

          getResults(page = 1) {

              this.$Progress.start();

              axios.get('api/usuario?page=' + page).then(({ data }) => (this.usuarios = data.data));

              this.$Progress.finish();
          },
          loadUsuarios(){

            // if(this.$gate.isAdmin()){
              axios.get("api/usuario").then(({ data }) => (this.usuarios = data.data));
            // }
          },

          editModal(usuario){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(usuario);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          createUsuario(){
              this.$Progress.start();

              this.form.post('api/usuario')
              .then((data)=>{
                if(data.data.success){
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.loadUsuarios();

                } else {
                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });

                  this.$Progress.failed();
                }
              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          },
          updateUsuario(){
              this.$Progress.start();
              this.form.put('api/usuario/'+this.form.id)
              .then((response) => {
                  // success
                  $('#addNew').modal('hide');
                  Toast.fire({
                    icon: 'success',
                    title: response.data.message
                  });
                  this.$Progress.finish();
                      //  Fire.$emit('AfterCreate');

                  this.loadUsuarios();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

          },
            downgradeUsuario(id){
                this.$Progress.start();
                this.form.put('api/usuario/downgrade/'+id)
                    .then((response) => {
                        // success
                        $('#addNew').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });
                        this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                        this.loadUsuarios();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });

            },
            upgradeUsuario(id){
                this.$Progress.start();
                this.form.put('api/usuario/upgrade/'+id)
                    .then((response) => {
                        // success
                        $('#addNew').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });
                        this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                        this.loadUsuarios();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });

            },
        deleteUsuario(id){
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.delete('api/usuario/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadUsuarios();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },

        },
        mounted() {

        },
        created() {
            this.$Progress.start();

            this.loadUsuarios();

            this.$Progress.finish();
        },
        filters: {
            truncate: function (text, length, suffix) {
                return text.substring(0, length) + suffix;
            },
        },
        computed: {
          filteredItems() {
            return this.autocompleteItems.filter(i => {
              return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
            });
          },
        },
    }
</script>
