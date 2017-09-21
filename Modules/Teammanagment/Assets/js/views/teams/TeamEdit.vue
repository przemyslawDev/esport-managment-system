<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <form v-on:submit.prevent>
                <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
                <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

                <input-text :name="'Name'" v-model="team.name" :placeholder="'Name'"></input-text>
                <input-text :name="'Tag'" v-model="team.tag" :placeholder="'Tag'"></input-text>

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
            sending: false,
            team: {},
        }
    },
    mounted() {
        this.getData();
    },
    props: ['id'],
    methods: {
        getData() {
            const th = this;
            this.loading = true;
            axios.get('/teammanagment/teams/team/' + this.id)
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
        submit() {
            this.clearNotifications();
            var data = {
                name: this.team.name,
                tag: this.team.tag
            }
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.put('/teammanagment/teams/' + this.id, data)
                .then(function(response) {
                    th.sending = false;
                    th.success += 'Data updated.'
                })
                .catch(function(error) {
                    let r = error.response.data;

                    if (r.errors) {
                        th.error += r.errors.name ? r.errors.name + ' ' : '';
                        th.error += r.errors.tag ? r.errors.tag + ' ' : '';
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
