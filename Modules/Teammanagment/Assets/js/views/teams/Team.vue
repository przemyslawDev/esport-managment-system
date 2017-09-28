<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading" class="col-md-offset-3 col-md-6">
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ team.name }}</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="http://babyinfoforyou.com/wp-content/uploads/2014/10/avatar-300x300.png" class="img-circle img-responsive"> </div>

                        <div class=" col-md-9 col-lg-9 ">
                            <table class="table table-user-information">
                                <tbody>
                                    <tr>
                                        <td>Tag</td>
                                        <td>{{ team.tag }}</td>
                                    </tr>

                                    <tr>
                                        <td>Games</td>
                                        <td>
                                            <span style="margin-right: 10px;" v-for="game in team.games">{{ game.name }}</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Manager</td>
                                        <template  v-if="team.manager">
                                            <td>{{ team.manager.nickname }}</td>
                                            <td v-if="canEditStatus"><button @click="detachManager(team.id)" class="btn btn-default">Detach Manager</button></td>
                                        </template>
                                        <td v-if="canEditStatus"><button @click="showManagersList" type="button" class="btn btn-default">Attach Manager</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <!-- -->
                </div>
                <div v-if="attach_managers_enabled">
                    <spinner :condition="managers_loading"></spinner>
                    <div v-if="!managers_loading">
                        <div v-if="managers_error" class="alert alert-danger" role="alert">{{ managers_error }}</div>
                        <div v-else>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nickname</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr @click="attachManager(team.id, manager.id)" v-for="manager in managers" class="clickable-row">
                                            <td>{{ manager.nickname }}</td>
                                        </tr>
                                    </tbody>
                                    <pagination :info="managers_pagination" :data="getManagers"></pagination>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            managers_error: '',
            loading: false,
            managers_loading: false,
            team: {},
            managers: [],
            managers_pagination: {
                current_page: null,
                total: null,
                per_page: null,
                last_page: null,
            },
            attach_managers_enabled: false
        }
    },
    mounted() {
        this.getData();
    },
    props: {
        id: {
            required: true
        },
        canedit: {
            type: String, // '1' or '0'
            default: false,
            required: true
        }
    },
    computed: {
        canEditStatus() {
            return ((this.canedit === '1') ? true : false);
        }
    },
    methods: {
        detachManager(team_id) {
            this.clearNotifications();
            const th = this;
            axios.get('/teammanagment/teams/' + team_id + '/manager/detach')
                .then(function(response) {
                    th.getData();
                    th.success += 'You detach this team.';
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                });
        },
        attachManager(team_id, manager_id) {
            this.clearNotifications();
            const th = this;
            axios.get('/teammanagment/teams/' + team_id + '/manager/' + manager_id + '/attach')
                .then(function(response) {
                    th.getData();
                    th.success += 'You attach manager.';
                    th.attach_managers_enabled = false;
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                });
        },
        getData() {
            this.clearNotifications();
            const th = this;
            this.loading = true;
            axios.get('/teammanagment/teams/team/' + this.id )
                .then(function(response) {
                    th.team = response.data;
                    th.loading = false;
                })
                .catch(function(error) {
                    let r = error.reponse.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading = false;
                });
        },
        showManagersList() {
            this.getManagers();
            this.attach_managers_enabled = true;
        },
        getManagers(page = 1) {
            this.clearNotifications();
            const th = this;
            this.managers_loading = true;

            axios.get('/teammanagment/managers/get/all?page=' + page )
                .then(function(response) {
                    th.managers = response.data.data;
                    th.managers_pagination.total = response.data.total;
                    th.managers_pagination.per_page = response.data.per_page;
                    th.managers_pagination.last_page = response.data.last_page;
                    th.managers_pagination.current_page = response.data.current_page;
                    th.managers_loading = false;
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.managers_error += 'Fatal error. ';
                    th.managers_error += r.message ? r.message + ' ' : '';
                    th.managers_loading = false;
                });
        },
        filterGames() {
            var team_games = this.team.games;

            var with_games = '';
            team_games.forEach( function (item, index) {
                with_games += '&games[]=' + item.id;
            });

            return with_games;
        },
        clearNotifications() {
            this.success = '';
            this.error = '';
            this.managers_error = '';
        }
    }
}
</script>
<style>
    .clickable-row {
        cursor: pointer;
    }

    .clickable-row :hover {
        opacity: 0.7;
    }
</style>
