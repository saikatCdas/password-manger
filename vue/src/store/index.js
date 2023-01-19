import {createStore} from 'vuex';
import axiosClient from '../axios';

const store = createStore({
    state:{
        user:{
            data: '',
            token: sessionStorage.getItem("TOKEN"),
          },
          notification: {
            show: false,
            type: 'success',
            message: ''
          },
          exportUrl:{},
          folders: [],
          vaultItems:{},
          paginationLinks:[]
    },
    getters:{},
    actions:{
        getAllVault({commit}){
            return axiosClient.get('/get-all-vault')
        },
        CreateVault({commit}, vaultData){
            return axiosClient.post('/create-vault', vaultData);
        },
        getFolder({commit}){
            return axiosClient.get('/get-folder')
                .then(({data})=>{
                    commit('setFolderName', data)
                })
        },
        createFolder({commit}, folderName){
            return axiosClient.post('/create-folder', {name:folderName})
                .then(({data})=>{
                    commit('setFolderName', data);
                })
        },
        exportVault({commit}){
            return axiosClient.get('export')
                .then((res)=>{
                    commit('setExportUrl', res.data);
                })
        },
        register({commit}, user) {
            return axiosClient.post('/register', user)
              .then(({data}) => {
                commit('setUser', data.user);
                commit('setToken', data.token)
                return data;
              })
          },
          login({commit}, user){
            return axiosClient.post('/login', user)
              .then(({data}) => {
                commit('setUser', data.user);
                commit('setToken', data.token)
                return data;
              })
          },
        logout({commit}) {
            return axiosClient.post('/logout')
              .then(response => {
                commit('logout')
                return response;
              })
          },
    },
    mutations:{
        setFolderName:(state, data) => {
            state.folders = data ;
        },
        setExportUrl:(state, data) => {
            state.exportUrl = data;
        },
        setUser: (state, user) => {
            state.user.data = user;
        },
        setToken: (state, token) => {
            state.user.token = token;
            sessionStorage.setItem('TOKEN', token);
        },
        logout: (state) => {
            state.user.token = null;
            state.user.data = {};
            sessionStorage.removeItem("TOKEN");
        },
        notify: (state, {message, type}) => {
            state.notification.show = true;
            state.notification.type = type;
            state.notification.message = message;
            setTimeout(() => {
              state.notification.show = false;
            }, 8000)
        },
    },
    modules:{}
})

export default store;
