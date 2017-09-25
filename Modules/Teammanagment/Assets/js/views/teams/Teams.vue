<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div v-else>
                <a class="btn btn-primary btn-sm" href="teams/create">Create</a>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Tag</tH>
                                <th>Games</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="team in teams">
                                <td>{{ team.name }}</td>
                                <td>{{ team.tag }}</td>
                                <td>
                                    <button @click="detachGame(team.id, game.id)" type="button" class="btn btn-default btn-game btn-sm" v-for="game in team.games">
                                        {{ game.name }} <i v-if="canEditStatus" class="glyphicon glyphicon-remove"></i>
                                    </button>
                                    <template v-if="canEditStatus">
                                        <button @click="(select == team.id) ? select = null : select = team.id" type="button" class="btn btn-default btn-circle">
                                            <i v-if="select != team.id" class="fa fa-plus"></i>
                                            <i v-else class="fa fa-minus"></i>
                                        </button>
                                        <select v-if="games && select && select == team.id" name="game" v-model="game" @change="attachGame(team.id, game)" class="form-control">
                                            <option v-for="game in games" v-if="!teamHasGame(team.games, game.id)" :value="game.id">{{ game.name }}</option>
                                        </select>
                                    </template>
                                </td>
                                <td>
                                    <div class="">
                                        <a :href="'teams/' + team.id" type="button" class="btn btn-info btn-sm">View</a>
                                        <a :href="'teams/' + team.id + '/edit'" type="button" class="btn btn-primary btn-sm">Edit</a>
                                        <a @click="deleteTeam(team.id)" type="button" class="btn btn-danger btn-sm">Delete</a>                                    
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <pagination :info="pagination" :data="getData"></pagination>
                    </table>
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
            teams: null,
            select: false,
            game: null,
            games: null,
            loading: false,
            pagination: {
                current_page: null,
                total: null,
                per_page: null,
                last_page: null,
            }
        }
    },
    props: {
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
    mounted() {
        this.getData();
        this.getGames();
    },
    methods: {
         getGames() {
            const th = this;
            axios.get('/teammanagment/games/get/all')
                .then(function(response) {
                    th.games = response.data.data;
                })
                .catch(function(error) {
                    let r = error.reponse.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                });
        },
        getData(page = 1) {
            const th = this;
            this.select = false;
            this.loading = true;
            
            axios.get('/teammanagment/teams/get/all?page=' + page)
                .then(function(response) {
                    th.teams = response.data.data;
                    th.pagination.total = response.data.total;
                    th.pagination.per_page = response.data.per_page;
                    th.pagination.last_page = response.data.last_page;
                    th.pagination.current_page = response.data.current_page;
                    th.loading = false;
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading = false;
                });
        },
        teamHasGame(team_games, game_id) {
            for(var i = 0; i < team_games.length; i++) {
                if (team_games[i].id == game_id) {
                    return true; 
                }
            }
            return false;
        },
        deleteTeam(id) {
            this.clearNotifications();
            const th = this;
            axios.delete('/teammanagment/teams/' + id)
                .then(function(response) {
                    th.getData();
                    th.success += 'Data deleted.';
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                });
        },
        attachGame(team_id, game_id) {
            if(this.canEditStatus) {
                this.clearNotifications();
                const th = this;

                axios.get('/teammanagment/teams' + '/' + team_id + '/games' + '/' + game_id + '/attach')
                    .then(function(response) {
                        th.getData();
                        th.success += 'Game attached.'
                    })
                    .catch(function(error) {
                        let r = error.response.data;
                        th.error += 'Fatal error. ';
                        th.error += r.message ? r.message + ' ' : '';
                    });
            }
        },
        detachGame(team_id, game_id) {
            if(this.canEditStatus) {
                this.clearNotifications();
                const th = this;

                axios.get('/teammanagment/teams' + '/' + team_id + '/games' + '/' + game_id + '/detach')
                    .then(function(response) {
                        th.getData();
                        th.success += 'Game detached.'
                    })
                    .catch(function(error) {
                        let r = error.response.data;
                        th.error += 'Fatal error. ';
                        th.error += r.message ? r.message + ' ' : '';
                    });
            }
        },
        clearNotifications() {
            this.success = '';
            this.error = '';
        }
    }
}
</script>

<style lang="css">

.btn-game {
    margin-left: 2px;
    margin-right: 2px;
}

</style>
