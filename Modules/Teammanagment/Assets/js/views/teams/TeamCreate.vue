<template>
    <div>
        <form v-on:submit.prevent>
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

            <input-text :name="'Name'" v-model="team.name" :placeholder="'Name'"></input-text>
            <input-text :name="'Tag'" v-model="team.tag" :placeholder="'Tag'"></input-text>

            <div v-if="!loading_games" class="form-group">
                <label>Game:</label>
                <select name="games" multiple class="form-control" v-model="team.games">
                    <option v-for="game in games" :value="game.id">{{ game.name }}</option>
                </select>
            </div>

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
            loading_games: false,
            team: {
                name: '',
                tag: '',
                games: []
            },
            games: null
        }
    },
    mounted() {
        this.getGames();
    },
    methods: {
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
        submit() {
            this.clearNotifications();
            var data = {
                name: this.team.name,
                tag: this.team.tag,
                games: this.team.games
            }
            
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.post('/teammanagment/teams', data)
                .then(function(response) {
                    th.sending = false;
                    th.success += 'Data created.'
                })
                .catch(function(error) {
                    let r = error.response.data;

                    if (r.errors) {
                        th.error += r.errors.name ? r.errors.name + ' ' : '';
                        th.error += r.errors.tag ? r.errors.tag + ' ' : '';
                        th.error += r.errors.games ? r.errors.games + ' ' : '';
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
