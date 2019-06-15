<template>
  <v-app
    id="inspire"
    dark
  >
    <v-navigation-drawer
      v-model="drawer"
      fixed
      clipped
      app
    >
    <friends></friends>
    
    </v-navigation-drawer>
    <v-toolbar
      color="red"
      dense
      fixed
      clipped-left
      app
    >
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title class="mr-5 align-center">
        <router-link :to="{ name: 'home'}"  tag="button">
        <span class="title">Chat App</span>
       </router-link>
      </v-toolbar-title>
      <v-spacer></v-spacer>
   
     
 <v-spacer></v-spacer>
     <!--
       if the user is auth show the notification
       else show the button register , login

       -->
     <v-badge overlap v-if="this.$store.getters.isAuth">
      <span slot="badge">{{unseenNotify.length}}</span>

       <v-menu 
       offset-y
       :nudge-width="200"
       >
      <v-avatar
        color="purple red--after"
        size="36"
         slot="activator"
      >
        <v-icon dark>notifications</v-icon>
      </v-avatar>     
      <v-list>
          <v-list-tile avatar v-for="(item,index) in notifications"
          @click="notificationDetail(item.id,index)"
          background-color="green"
           :key="item.id">
            <v-list-tile-avatar>
              <img :src="`${$store.getters.baseurl}storage/${item.senderInfo.avatar}`" alt="John">
            </v-list-tile-avatar>

            <v-list-tile-content>
              <v-list-tile-title>{{item.senderInfo.name}}</v-list-tile-title>
              <v-list-tile-sub-title>{{item.content}}</v-list-tile-sub-title>
            </v-list-tile-content>

            <v-list-tile-action>
            </v-list-tile-action>
          </v-list-tile>
        <v-divider></v-divider>
        </v-list>


    </v-menu>
    </v-badge>
      <v-toolbar-items v-else>
        <router-link :to="{ name: 'login'}"  tag="button" text-xs-center>
         <v-btn class="title" fab dark  depressed  color="red">
           <v-icon>account_circle</v-icon>
         </v-btn>
       </router-link>
       <router-link :to="{ name: 'register'}"  tag="button" text-xs-center>
         <v-btn class="title"  fab dark depressed  color="red">
           <v-icon>person_add</v-icon>
           </v-btn>
       </router-link>
    </v-toolbar-items>
  
        
    </v-toolbar>
    <v-content>
       <router-view></router-view>
    </v-content>

   <v-layout row justify-center>
    <v-dialog v-model="dialog_friend" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">
        <img :src="`${this.$store.getters.baseurl}storage/${this.notify_selected.senderInfo.avatar}`" :alt="this.notify_selected.senderInfo.name">
          {{ this.notify_selected.senderInfo.name}}</v-card-title>
        <v-card-text>{{this.notify_selected.content}}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click.native="accpetFriend()"><v-icon>person_add</v-icon></v-btn>
          <v-btn color="red darken-1" flat @click.native="dialog_friend = false"><v-icon>person_add_disabled</v-icon></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>

  </v-app>
</template>

<script>
import Echo from "laravel-echo";
import friends from "../js/components/user/friends.vue";
export default {
  components: {
    friends: friends
  },
  created() {
    // if the current token user exsit connect the old info he have
    if (this.$store.getters.user) {
      let remember_token = this.$store.getters.user.remember_token;
      window.axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.getters.user.remember_token;
      window.io = require("socket.io-client");
      let token = document.head.querySelector('meta[name="csrf-token"]');
      // if user is Auth  create Auth Channel
      if (remember_token) {
        window.Echo = new Echo({
          broadcaster: "socket.io",
          host: "127.0.0.1:6001",
          csrfToken: token.content,
          auth: {
            headers: {
              Authorization: "Bearer " + remember_token
            }
          }
        });
      }

      let AppIns = this;
      new Promise(function(resolve, reject) {
        // refresh data ,which stored in browser
        AppIns.$store
          .dispatch("getUser")
          .then((res) => {
            resolve();
          })
          .catch((err) => {
            AppIns.$store.dispatch('updateUser',null);
            AppIns.$router.push({ name: "login" });
          });
        //   resolve(resolve);
      }).then(function() {
        // get notifications for user
        AppIns.$store
          .dispatch("getNotification")
          .then((res) => {
            AppIns.unseenNotify = res.data.unseen;
            AppIns.notifications = AppIns.unseenNotify.concat(res.data.seen);
          })
          .catch((err) => {
            AppIns.$toaster.error(
              "Something happened error in Notification, try again or contact the support"
            );
          });
      });

      // listen to notificaitons
      if (this.$store.getters.isAuth) {
        window.Echo.private(
          `notifications.${this.$store.getters.user.id}`
        ).listen("NotificationRequest", (e) => {
          // add new notification for unseen list
          //unseenNotify it is just use for count unseen notifications
          this.unseenNotify.push(e.data);
          // add new notification for notifications  list (main list)
          this.notifications.push(e.data);
          // reverse order for notications to newest
          this.notifications.reverse();
        });
      }
    }
  },
  methods: {
    //show details for notifications which user select it 
    notificationDetail(notify_id, index) {
      this.notify_selected = this.notifications[index];
      this.friend_id = this.notify_selected.senderInfo.id;
      this.dialog_friend = true;

      this.$store
        .dispatch("seenNotificaiton", { id: this.notify_selected.notify_id })
        .then((res) => {
          // remove the notification after see it form unseen list ..
          this.unseenNotify.pop(this.notify_selected);
        })
        .catch((err) => {});
      //  this.$router.push({name:'notifications',params:{id:notify_id}});
    },
    accpetFriend() {
      let data = {
        friend_id: this.friend_id,
        notiy_id: this.notify_selected.notify_id
      };
      this.$store
        .dispatch("AcceptFriend")
        .then((res) => {
          if (res.data.success) {
            this.$toaster.success(res.data.success);
          } else if (res.data.error) {
            this.$toaster.error(res.data.error);
          }
        })
        .catch((err) => {
          this.$toaster.error(
            "Something happened error, try again or contact the support"
          );
        });
      this.dialog_friend = false;
    }
  },
  data: () => ({
    drawer: null, // for menu
    dialog: false, //
    dialog_friend: false, // show detail for requet friend
    unseenNotify: [], // list for unseen notifications
    friend_id: 0,
    notify_selected: { // tmp for selected notification
      senderInfo: { id: 0, avatar: "", name: "" },
      content: ""
    },
    notifications: [] // list for all notifications (seen(coming from server implicit) + unseen)
  }),
  props: {
    source: String
  }
};
</script>