<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <form v-on:submit.prevent>
                <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
                <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

                <input-text :name="'Email'" v-model="user.email" :placeholder="'Email'"></input-text>
                <div v-if="!loading_roles" class="form-group">
                    <label>Roles:</label>
                    <select name="roles" multiple class="form-control" v-model="roles_ids">
                        <option v-for="role in roles" :value="role.id">{{ role.display_name }}</option>
                    </select>
                </div>

                <template v-if="roleHasSelected(roles_ids, getRoleByName(roles, 'manager').id)">
                    <h3>Manager Data</h3>
                    <input-text :name="'Nickname'" v-model="user.employee.manager.nickname" :placeholder="'Nickname'"></input-text>
                    <div v-if="!loading_games" class="form-group">
                        <label>Games</label>
                        <select name="manager_games" multiple class="form-control" v-model="games_ids">
                            <option v-for="game in games" :value="game.id">{{ game.name }}</option>
                        </select>
                    </div>
                </template>

                <spinner-button :condition="sending" :submit="submit"></spinner-button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            loading: false,
            loading_roles: false,
            loading_games: false,
            games: [],
            sending: false,
            user: {},
            roles_ids: [],
            games_ids: [],
            roles: [],
        }
    },
    watch: {
        user: function(user) {
            var array = [];
            user.roles.forEach(function(role) {
                array.push(role.id);
            }, this);
            this.roles_ids = array;

            array = [];
            user.employee.manager.games.forEach(function(game) {
                array.push(game.id);
            }, this);
            this.games_ids = array;
        }
    },
    mounted() {
        this.getData();
        this.getRoles();
        this.getGames();
    },
    props: ['id'],
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
                    th.loading_roles = false;
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading_roles = false;
                });
        },
        getData() {
            const th = this;
            this.loading = true;
            axios.get('/users/user/' + this.id)
                .then(function(response) {
                    th.user = response.data;
                    th.loading = false;
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading = false;
                });
        },
        submit() {
            this.clearNotifications();
            var data = {
                email: this.user.email,
                roles: this.roles_ids
            };

            if (this.roleHasSelected(roles_ids, this.getRoleByName(this.roles, 'manager').id)) {
                data.nickname = this.user.employee.manager.nickname;
                data.manager_games = this.games_ids;
            }

            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.put('/users/' + this.id, data)
                .then(function(response) {
                    th.sending = false;
                    th.success += 'Data updated.'
                })
                .catch(function(error) {
                    let r = error.response.data;

                    if (r.errors) {
                        th.error += r.errors.email ? r.errors.email + ' ' : '';
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
