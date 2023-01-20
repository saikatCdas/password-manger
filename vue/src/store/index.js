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
          vaultItem:{},
          paginationLinks:[]
    },
    getters:{},
    actions:{
        deleteSelectedVaultItem({commit}, itemId){
            return axiosClient.delete(`/delete-selected-vault-item/${itemId}`);
        },
        getItem({commit}, id){
            return axiosClient.get(`/get-item/${id}`)
                .then(({data})=>{
                    commit('setVaultItem', data);
                })
        },
        getAllVault({commit}, {type, url}){

            //converting type into a string
            let queryString;
            if(!url){
                queryString = Object.keys(type).map(key => key + '=' + type[key]).join('&');
            }
            //checking if request has url
            url = url || `/get-all-vault/${queryString}`
            return axiosClient.get(url)
                .then(({data})=>{
                    commit('setVaultItems', data);
                });
        },

        // Create or Update Vault Item
        manageVault({commit}, vaultData){
            if(!vaultData.id){
                return axiosClient.post('/create-vault', vaultData);
            }else{
                return axiosClient.put('/update-vault', vaultData);
            }
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
        setVaultItem:(state, vaultItem) => {
            state.vaultItem = vaultItem.data;
        },
        setVaultItems:(state, allVaultData)=>{
            state.vaultItems = allVaultData.data;
            state.paginationLinks = allVaultData.meta.links;
        },
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
