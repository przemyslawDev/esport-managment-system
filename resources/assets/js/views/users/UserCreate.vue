<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-body">
                <label>Type</label>
                <select class="form-control" v-model="user.type">
                    <option v-for="option in typeOptions" :value="option.value">{{ option.text }}</option>
                </select>
            </div>
        </div>
        <form v-on:submit.prevent>
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

            <div class="form-group">
                <label>Email</label>
                <input class="form-control" v-model="user.email" placeholder="Email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" v-model="user.password">
            </div>
            <div v-if="!loading_roles" class="form-group">
                <label>Roles:</label>
                <select multiple class="form-control" v-model="user.roles">
                    <option v-for="role in roles" :value="role.id">{{ role.display_name }}</option>
                </select>
            </div>

            <template v-if="user.type === 'employee'">
                <hr>
                <div class="form-group">
                    <label>Firstname</label>
                    <input class="form-control" v-model="user.employee.firstname" placeholder="firstname"></input>
                </div>
                <div class="form-group">
                    <label>Lastname</label>
                    <input class="form-control" v-model="user.employee.lastname" placeholder="lastname"></input>
                </div>
                <div class="form-group">
                    <label>Office</label>
                    <input class="form-control" v-model="user.employee.office" placeholder="office"></input>
                </div>
                <div class="form-group">
                    <label>Birthdate</label>
                    <input class="form-control" v-model="user.employee.birthdate" placeholder="MM-DD-YYYY"></input>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" v-model="user.employee.status">
                        <option v-for="status in statusOptions" :value="status.value">{{ status.text }}</option>
                    </select>
                </div>
            </template>

            <button v-if="sending" class="btn btn-success"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i></button>
            <button v-else @click="submit" class="btn btn-success">Submit</button>
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
            user: {
                email: '',
                password: '',
                roles: [],
                employee: {
                    firstname: '',
                    lastname: '',
                    office: '',
                    birthdate: '',
                    status: 1
                },
                type: 'none'
            },
            roles: null,
            typeOptions: [
                {
                    text: "None",
                    value: 'none'
                },
                {
                    text: 'Employee',
                    value: 'employee'
                }
            ],
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
    mounted() {
        this.getRoles();
    },
    methods: {
        getRoles() {
            const th = this;
            this.loading_roles = true;
            axios.get('/roles/get/all')
                .then(function(response) {
                    th.roles = response.data;
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
                firstname: this.user.employee.firstname,
                lastname: this.user.employee.lastname,
                office: this.user.employee.office,
                birthdate: this.user.employee.birthdate,
                status: this.user.employee.status
            }
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.post('/users', data)
            .then(function (response) {
                th.sending = false;
                th.success += 'Data created.'
            })
            .catch(function (error) {
                let r = error.response.data;

                if(r.errors) {
                    th.error += r.errors.email ? r.errors.email + ' ' : '';
                    th.error += r.errors.password ? r.errors.password + ' ' : '';
                    th.error += r.errors.roles ? r.errors.roles + ' ' : '';
                    th.error += r.errors.type ? r.errors.type + ' ' : '';
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
