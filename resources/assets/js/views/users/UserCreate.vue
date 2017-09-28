<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-body">
                <label>Type</label>
                <select name="type" class="form-control" v-model="user.type">
                    <option v-for="option in typeOptions" :value="option.value">{{ option.text }}</option>
                </select>
            </div>
        </div>
        <form v-on:submit.prevent v-if="user.type">
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

            <input-text :name="'Email'" v-model="user.email" :placeholder="'Email'"></input-text>
            <div class="form-group">
                <label>Password</label>
                <input name="password" type="password" class="form-control" v-model="user.password">
            </div>
            <div v-if="!loading_roles" class="form-group">
                <label>Roles:</label>
                <select name="roles" multiple class="form-control" v-model="user.roles">
                    <option v-for="role in roles" :value="role.id">{{ role.display_name }}</option>
                </select>
            </div>

            <hr />
            <template v-if="user.type === 'employee'">
                <h3>Employee Data</h3>
                <input-text :name="'Firstname'" v-model="user.employee.firstname" :placeholder="'Firstname'"></input-text>
                <input-text :name="'Lastname'" v-model="user.employee.lastname" :placeholder="'Lastname'"></input-text>
                <input-text :name="'Office'" v-model="user.employee.office" :placeholder="'Office'"></input-text>
                <div class="form-group">
                    <label>Birthdate</label>
                    <datepicker name="birthdate" v-model="user.employee.birthdate" :format="'MM-dd-yyyy'" :bootstrapStyling="true" :calendar-button="true" :calendar-button-icon="'fa fa-calendar'"></datepicker>
                </div>
            </template>

            <hr />
            <template v-if="roleHasSelected(user.roles, getRoleByName(roles, 'manager').id)">
                <h3>Manager Data</h3>
                <input-text :name="'Nickname'" v-model="user.employee.manager.nickname" :placeholder="'Nickname'"></input-text>
                <div v-if="!loading_games" class="form-group">
                    <label>Games</label>
                    <select name="manager_games" multiple class="form-control" v-model="user.employee.manager.games">
                        <option v-for="game in games" :value="game.id">{{ game.name }}</option>
                    </select>
                </div>
            </template>

            <spinner-button :condition="sending" :submit="submit"></spinner-button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            sending: false,
            loading_roles: false,
            loading_games: false,
            user: {
                email: '',
                password: '',
                roles: [],
                employee: {
                    firstname: '',
                    lastname: '',
                    office: '',
                    birthdate: '',
                    manager: {
                        nickname: '',
                        games: []
                    }
                },
                type: null
            },
            roles: [],
            all_roles: [],
            games: [],
            typeOptions: [
                {
                    text: "None",
                    value: 'none'
                },
                {
                    text: 'Employee',
                    value: 'employee'
                }
            ]
        }
    },
    watch: {
        user: {
            handler(user) {
               if(user.type == 'none') { 
                   this.roles = [
                       this.getRoleByName(this.all_roles, 'system_admin'),
                       this.getRoleByName(this.all_roles, 'admin')
                   ];
               } else {
                   this.roles = this.all_roles;
               }
            },
            deep: true
        }
    },
    mounted() {
        this.getRoles();
        this.getGames();
    },
    methods: {
        getRoleByName(roles, role_name) {
            for (var i = 0; i < roles.length; i++) {
                if (roles[i].name === role_name) {
                    return roles[i];
                }
            }
            return {};
        },
        roleHasSelected(selected_roles_ids, role_id) {
            for (var i = 0; i < selected_roles_ids.length; i++) {
                if (selected_roles_ids[i] == role_id) {
                    return true;
                }
            }
            return false;
        },
        getGames() {
            const th = this;
            this.loading_games = true;
            axios.get('/teammanagment/games/get/all')
                .then(function(response) {
                    th.games = response.data.data;
                    th.loading_games = false;
                })
                .catch(function(error) {
                    let r = error.reponse.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading_games = false;
                });
        },
        getRoles() {
            const th = this;
            this.loading_roles = true;
            axios.get('/roles/get/all')
                .then(function(response) {
                    th.roles = response.data;
                    th.all_roles = response.data;
                    th.loading_roles = false;
                })
                .catch(function(error) {
                    let r = error.reponse.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading_roles = false;
                });
        },
        submit() {
            this.clearNotifications();
            var data = {
                email: this.user.email,
                password: this.user.password,
                roles: this.user.roles,
                type: this.user.type,
            }
            if (this.user.type === 'employee') {
                data.firstname = this.user.employee.firstname;
                data.lastname = this.user.employee.lastname;
                data.office = this.user.employee.office;
                data.birthdate = this.user.employee.birthdate;
            }

            if (this.roleHasSelected(this.user.roles, this.getRoleByName(this.all_roles, 'manager').id)) {
                data.nickname = this.user.employee.manager.nickname;
                data.manager_games = this.user.employee.manager.games;
            }

            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.post('/users', data)
                .then(function(response) {
                    th.sending = false;
                    th.success += 'Data created.'
                })
                .catch(function(error) {
                    let r = error.response.data;

                    if (r.errors) {
                        th.error += r.errors.email ? r.errors.email + ' ' : '';
                        th.error += r.errors.password ? r.errors.password + ' ' : '';
                        th.error += r.errors.roles ? r.errors.roles + ' ' : '';
                        th.error += r.errors.type ? r.errors.type + ' ' : '';
                        th.error += r.errors.firstname ? r.errors.firstname + ' ' : '';
                        th.error += r.errors.lastname ? r.errors.lastname + ' ' : '';
                        th.error += r.errors.office ? r.errors.office + ' ' : '';
                        th.error += r.errors.birthdate ? r.errors.birthdate + ' ' : '';
                        th.error += r.errors.nickname ? r.errors.nickname + ' ' : '';
                    } else {
                        th.error += 'Fatal error. '
                        th.error += r.message ? r.message + ' ' : '';
                    }
                    th.sending = false;
                });
        },
        clearNotifications() {
            this.success = '';
            this.error = '';
        }
    }
}
</script>
