<template>
    <form v-on:submit.prevent>
        <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

        <div class="form-group">
            <label>Firstname</label>
            <input class="form-control" v-model="employee.firstname" placeholder="Firstname">
        </div>
        <div class="form-group">
            <label>Lastname</label>
            <input class="form-control" v-model="employee.lastname" placeholder="Lastname">
        </div>
        <div class="form-group">
            <label>Office</label>
            <input class="form-control" v-model="employee.office" placeholder="Office">
        </div>
        <div class="form-group">
            <label>Birthdate</label>
            <input class="form-control" v-model="employee.birthdate" placeholder="MM-DD-YYYY">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" v-model="employee.status">
                <option v-for="status in statusOptions" :value="status.value">{{ status.text }}</option>
            </select>
        </div>
        <button v-if="sending" class="btn btn-success"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i></button>
        <button v-else @click="submit" class="btn btn-success">Submit</button>
    </form>
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            sending: false,
            employee: {
                firstname: '',
                lastname: '',
                office: '',
                birthdate: '',
                status: 1
            },
            statusOptions: [
                {
                    text: '0',
                    value: 0
                },
                {
                    text: '1',
                    value: 1
                },
                {
                    text: '2',
                    value: 2
                }
            ]
        }
    },
    methods: {
        submit() {
            this.clearNotifications();
            var data = {
                firstname: this.employee.firstname,
                lastname: this.employee.lastname,
                office: this.employee.office,
                birthdate: this.employee.birthdate,
                status: this.employee.status
            }
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.post('/administration/employees', data)
            .then(function (response) {
                th.sending = false;
                th.success += 'Data created.'
            })
            .catch(function (error) {
                let r = error.response.data;

                if(r.errors) {
                    th.error += r.errors.firstname ? r.errors.firstname + ' ' : '';
                    th.error += r.errors.lastname ? r.errors.lastname + ' ' : '';
                    th.error += r.errors.office ? r.errors.office + ' ' : '';
                    th.error += r.errors.birthdate ? r.errors.birthdate + ' ' : '';
                    th.error += r.errors.status ? r.errors.status + ' ' : '';
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
