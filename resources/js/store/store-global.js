import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: sessionStorage.getItem('token') || "",
    },
    getters: {
        loggedIn(state){
            return state.token !== "";
        },
        isAdmin(state){
            console.log(state);
            return state.user.type == "a";
        },
        
    },
    mutations: {
        clearUserAndToken: (state) => {
            state.user = null;
            state.token = "";
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        clearUser: (state) => {
            state.user = null;
            sessionStorage.removeItem('user');
        },
        clearToken: (state) => {
            state.token = "";
            sessionStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        setUser: (state, user) => {
            console.log(JSON.stringify(user));
            state.user = user;
            sessionStorage.setItem('user', JSON.stringify(user));
        },
        setToken: (state, token) => {
            state.token = token;
            sessionStorage.setItem('token', token);
            axios.defaults.headers.common.Authorization = "Bearer " + token;
        },
        loadTokenAndUserFromSession: (state) => {
            state.token = "";
            state.user = null;
            let token = sessionStorage.getItem('token');
            let user = sessionStorage.getItem('user');
            if (token) {
                state.token = token;
                axios.defaults.headers.common.Authorization = "Bearer " + token;
            }
            if (user) {
                state.user = JSON.parse(user);
            }
        },
    },

    actions: {
        retrieveToken(context, credentials){
            return new Promise((resolve, reject) => {
                axios.post('api/login', {
                    email: credentials.email,
                    password: credentials.password
                })
                    .then(response => {
                        console.log(response.data);
                        const token = response.data.token.access_token;
                        const user = response.data.user;
                        context.commit('setToken', token);
                        context.commit('setUser', user);
                        resolve(response);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error);
                    });
            });
        },

        destroyToken(context){
            if (context.getters.loggedIn){
                return new Promise((resolve, reject) => {
                    axios.post('api/logout')
                        .then(response => {
                            context.commit('clearUserAndToken');
                            resolve(response);
                        })
                        .catch(error => {
                            context.commit('clearUserAndToken');
                            reject(error);
                        });
                })
            }
        },
        register(context, data){
            return new Promise((resolve, reject) => {
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
 
                let formData = new FormData();
                formData.append('name', data.name);
                formData.append('email', data.email);
                formData.append('password', data.password);
                formData.append('nif', data.nif);
                formData.append('photo', data.photo);
                formData.append('type', data.type);

                axios.post('/api/register', formData, config)
                .then(function (response) {
                    resolve(response);
                })
                .catch(function (error) {
                    reject(error);
                });
            })
        },
        editUser(context, data){
            return new Promise((resolve, reject) => {
                axios.put('api/users/' + data.user.id, data.user)
                    .then(response=>{
                        if(data.photo != ''){
                            context.dispatch('uploadPhoto', data);
                        }
                        context.commit('setUser', response.data.data);
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            })
        },
        uploadPhoto(content, data){
            return new Promise((resolve, reject) => {
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                let formData = new FormData();
                formData.append('photo', data.photo);
                formData.append('id', data.user.id);

                axios.post('api/photo', formData, config)
                .then(response => {
                    resolve(response);
                })
                .catch(error =>{
                    reject(error);
                })

            })
        },
        addUser(context, data){
            return new Promise((resolve, reject) => {
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
                let formData = new FormData();
                formData.append('name', data.name);
                formData.append('email', data.email);
                formData.append('password', data.password);
                formData.append('photo', data.photo);
                formData.append('type', data.type);

                axios.post('/api/addUser', formData, config)
                .then(function (response) {
                    resolve(response);
                })
                .catch(function (error) {
                    reject(error);
                });
            })
        },
    }
});
