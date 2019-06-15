import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        //user => stored in localStorage because HTTP it is connectionless
        // just login 1-after logout  2- after server delete Tocken
        user: JSON.parse(localStorage.getItem('user') !== "undefined" ? localStorage.getItem('user') : null) || null,
        //check the login done complete
        isLogin: false,
        // stored all friend Request Here...
        friendRequests: [],
        // stored all notificaitons Here...
        notificaitons: [],
        // in app user just can use one component
        privateChannel: '', //this for private chat
        roomChannel: '', // this for room chat
        currentUserInRoom: [], //for room chat ... store current users in room here
        messages: [], // store messages in chat ... here


    },
    mutations: {
        setUser(state, payload) {
            state.user = payload;
        },
        setFriendRequest(state, payload) {
            state.friendRequests = payload;
        },
        setNotification(state, payload) {
            state.notificaitons = payload;
        },
        isLogin(state, data) {
            state.isLogin = data;
        },
        auth_success(state, payload) {
            state.user = payload;
            localStorage.setItem('user', JSON.stringify(payload));

        },
        logout_success(state) {
            state.user = {},
                localStorage.removeItem('user');
        },
        setUserActivity(state, payload) {
            state.useractivity = payload;
        },
        setPrivateChannel(state, payload) {
            state.privateChannel = payload
        },
        setRoomChannel(state, payload) {
            state.roomChannel = payload;
        },
        setCurrentUsrRoom(state, payload) {
            state.currentUserInRoom = payload;
        },
        addUserRoom(state, payload) {
            state.currentUserInRoom.push(payload);
        },
        removeUserRoom(state, payload) {
            state.currentUserInRoom.pop(payload);
        },
        setMessage(state, payload) {
            state.messages = payload;
        }

    },
    actions: {

        // use => reutrn new Promise()
        // to handle the result in same place and show the message for user. ..
        setRoomChannel({
            commit
        }, payload) {
            commit('setRoomChannel', payload);
        },
        setCurrentUserInRoom({
            commit
        }, payload) {
            commit('setCurrentUsrRoom', payload)
        },
        setPrivateChannel({
            commit
        }, payload) {
            commit('setPrivateChannel', payload);
        },
        addUserToRoom({
            commit
        }, payload) {
            commit('addUserRoom', payload);
        },
        removeUserFromRoom({
            commit
        }, payload) {
            commit('removeUserRoom', payload);
        },
        login({
            commit
        }, user) {
            return new Promise((resolve, reject) => {
                commit('isLogin', true);
                axios.post(`${this.getters.baseurl}api/login`, user).then((res) => {
                    axios.defaults.headers.common['Authorization'] = res.data.token;
                    res.data.authUser.remember_token = res.data.token;
                    commit('auth_success', res.data.authUser);
                    resolve(res)
                }).catch((err) => {
                    commit('isLogin', false)
                    localStorage.removeItem('user')
                    reject(err)
                });

            })
        },
        logout({
            commit
        }) {
            return new Promise((resolve, reject) => {
                axios.post(`${this.getters.baseurl}api/logout`)
                    .then((res) => {
                        commit('logout_success');
                        localStorage.removeItem('user')

                        delete window.axios.defaults.headers.common['Authorization']

                        resolve(res);
                    }).catch((err) => {
                        reject(err);
                    })
            });

        },
        updateProfile({
            commit
        }, dataObj) {
            return new Promise((resolve, reject) => {
                let data = new FormData();
                if (dataObj.tempImageAsFile != null) {
                    data.append('avater', dataObj.tempImageAsFile, dataObj.tempImageAsFile.name)
                }
                data.append('name', dataObj.name)
                data.append('email', dataObj.email)
                data.append('password', dataObj.password)
                data.append('password_confirmation', dataObj.password_confirmation)
                // use post to update data for user nested by put .. because  put have problem with image
                axios.post(`${this.getters.baseurl}api/user/${dataObj.user_id}`, data, {
                        headers: {
                            'accept': 'application/json',
                            'Content-Type': `multipart/form-data;`,
                        }
                    })
                    .then((res) => {
                        resolve(res);
                    }).catch((err) => {
                        reject(err);
                    })

            });
        },
        register({
            commit
        }, user) {
            return new Promise((resolve, reject) => {
                axios.post(`${this.getters.baseurl}api/register`, user)
                    .then((res) => {
                        axios.defaults.headers.common['Authorization'] = res.data.token;
                        res.data.authUser.remember_token = res.data.token;
                        commit('auth_success', res.data.authUser);
                        resolve(res)
                        commit('isLogin', true)

                        resolve(res);

                    }).catch((err) => {
                        reject(err);
                    });
            })
        },
        getRooms({
            commit
        }, payload) {
            return new Promise((resolve, reject) => {

                axios.get(`${this.getters.baseurl}api/rooms`)
                    .then((res) => {
                        resolve(res);

                    })
                    .catch((err) => {
                        reject(err);

                    });

            });
        },
        blockFriend({
            commit
        }, data) {
            return new Promise((resolve, reject) => {
                axios.post(`${this.getters.baseurl}api/blockUser`, data)
                    .then((res) => {
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);

                    })

            })

        },
        DeleteFriend({
            commit
        }, data) {
            return new Promise((resolve, reject) => {

                axios.delete(`${this.getters.baseurl}api/friend/${data.friend_id}`)
                    .then((res) => {
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);

                    })
            })
        },
        getFriends({
            commit
        }, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`${this.getters.baseurl}api/friend`)
                    .then((res) => {
                        resolve(res);

                    })
                    .catch((err) => {
                        reject(err);

                    });
            });
        },
        AcceptFriend({
            commit
        }, payload) {
            return new Promise((resolve, reject) => {

                axios
                    .post(`${this.getters.baseurl}api/notifyAcceptFriend`, data)
                    .then((res) => {
                        //  this.notifications = res.data;
                        resolve(res);

                    })
                    .catch((err) => {
                        reject(err);

                    });


            });
        },
        getUser({
            commit
        }, payload) {

            return new Promise((resolve, reject) => {

                axios
                    .get(`${this.getters.baseurl}api/user`)
                    .then((result) => {
                        let user = result.data.user;
                        user.remember_token = result.data.token;
                        window.axios.defaults.headers.common["Authorization"] =
                            "Bearer " + user.remember_token;
                        commit('auth_success', user)
                        localStorage.setItem("user", JSON.stringify(user));
                        resolve();
                    })
                    .catch((err) => {

                        reject(err);
                    });

            })

        },
        seenNotificaiton({
            commit
        }, data) {

            return new Promise((resolve, reject) => {
                axios.put(`${this.getters.baseurl}api/seenNotification/${data.id}`, {})
                    .then((res) => {
                        resolve(res);
                    }).catch((err) => {
                        reject(err);
                    })
            })

        },
        getNotification() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`${this.getters.baseurl}api/mynotification`)
                    .then((res) => {
                        resolve(res);

                    })
                    .catch((err) => {
                        reject(err);
                    })
            })
        },
        addFriend({
            commit
        }, data) {
            return new Promise((resolve, reject) => {

                axios.post(`${this.getters.baseurl}api/notifyRequestFriend`, data)
                    .then((res) => {
                        resolve(res);
                    }).catch((err) => {
                        reject(err)
                    })

            })
        },
        sendMessage({
            commit
        }, data) {
            return new Promise((resolve, reject) => {
                axios.post(`${this.getters.baseurl}api/public`, data)
                    .then((res) => {
                        resolve(res);
                    }).catch((err) => {
                        reject(err);
                    })

            });
        },
        getMessage({
            commit
        }, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`${this.getters.baseurl}message/${payload.id}/${payload.type_chat}`)
                    .then((res) => {
                        if (Array.isArray(res.data)) {
                            commit('setMessage', res.data);
                        }
                        resolve(res);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        updateUser({
            commit
        }, payload) {
            commit('auth_success', payload);
        }
    },
    getters: {
        // get current User is Auth
        user(state) {
            return state.user;
        },
        // check the user is Auth or not
        isAuth(state) {
            return ((state.user && (typeof (state.user.remember_token) !== "undefined")));
        },
        friendRequest(state) {
            return state.friendRequests;
        },
        baseurl(state) {
            return window.axios.defaults.baseURL;
        },
        notificaitons(state) {
            return state.notificaitons;
        },
        roomChannel(state) {
            return state.roomChannel;
        },
        privateChannel(state) {
            return state.privateChannel;
        },
        currentUserInRoom(state) {
            return state.currentUserInRoom;
        },
        messages(state) {
            return state.messages;
        }
    },
});

export default store;
