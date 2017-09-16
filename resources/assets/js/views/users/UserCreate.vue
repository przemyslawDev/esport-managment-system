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

            <input-text :name="'Email'" v-model="user.email" :placeholder="'Email'"></input-text>
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
                <input-text :name="'Firstname'" v-model="user.employee.firstname" :placeholder="'Firstname'"></input-text>
                <input-text :name="'Lastname'" v-model="user.employee.lastname" :placeholder="'Lastname'"></input-text>
                <input-text :name="'Office'" v-model="user.employee.office" :placeholder="'Office'"></input-text>
                <div class="form-group">
                    <label>Birthdate</label>
                    <datepicker v-model="user.employee.birthdate" :format="'MM-dd-yyyy'" :bootstrapStyling="true" 
                    :calendar-button="true" :calendar-button-icon="'fa fa-calendar'"></datepicker>
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
            user: {
                email: '',
                password: '',
                roles: [],
                employee: {
                    firstname: '',
                    lastname: '',
                    office: '',
                    birthdate: ''
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
            }
            if(this.user.type === 'employee') {
                data.firstname = this.user.employee.firstname;
                data.lastname = this.user.employee.lastname;
                data.office = this.user.employee.office;
                data.birthdate = this.user.employee.birthdate;
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
