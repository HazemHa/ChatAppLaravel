<template>
      <v-list dense v-if="this.$store.getters.isAuth">
           <v-list-tile @click="">
          <v-list-tile-action>
            <v-icon color="grey darken-1">settings</v-icon>
          </v-list-tile-action>
          <v-list-tile-title class="grey--text text--darken-1"><router-link :to="{ name: 'profile'}" tag="button"> Profile</router-link></v-list-tile-title>
        </v-list-tile>

        <v-subheader class="mt-3 grey--text text--darken-1">Friends</v-subheader>
        <v-list dense>
          <v-list-tile v-for="(item,index) in friends" :key="item.id" 
          avatar
          @click=""
         >
            <v-list-tile-avatar>
              <img :src="`${$store.getters.baseurl}storage/${NotMe(item.friendInfo.id)?item.friendInfo.avatar:item.userInfo.avatar}`" alt="">
            </v-list-tile-avatar>
            <v-list-tile-title v-text="NotMe(item.friendInfo.id)?item.friendInfo.name:item.userInfo.name"></v-list-tile-title>

             <v-list-tile-action>
               <v-layout  align-center justify-end row fill-height>

                <v-btn  flat icon color="green" @click="createNewChat(NotMe(item.friendInfo.id)?item.friendInfo.id:item.userInfo.id,item.isBlock)">
                <v-icon dark>chat</v-icon>
                </v-btn>

               <v-btn  flat icon :color="item.isBlock == '0'?'white':'red'" @click="blockFriend(NotMe(item.friendInfo.id)?item.friendInfo.id:item.userInfo.id,index,item.isBlock == 0?1:0)">
                <v-icon dark>block</v-icon>
                </v-btn>

               <v-btn  flat icon @click="DeleteFriend(NotMe(item.friendInfo.id)?item.friendInfo.id:item.userInfo.id,index)">
               <v-icon dark>delete</v-icon>
               </v-btn>
      </v-layout>
            </v-list-tile-action>
            
          </v-list-tile>
        </v-list>
       
      </v-list>
</template>
<script>
export default {
  created() {
    if (this.$store.getters.isAuth) {
      // get data for myFriend
      this.$store
        .dispatch("getFriends")
        .then((res) => {
          this.friends = res.data;
        })
        .catch((err) => {});
    }
  },
  data: () => ({
    friends: [] // s
  }),
  methods: {
    // to hide the user info and display friends info
    NotMe(id) {
      return this.$store.getters.user.id != id;
    },
    // go to private chat
    createNewChat(receiver_id, isBlock) {
      if (isBlock == 0) {
        this.$router.push({ name: "private", params: { id: receiver_id } });
      } else {
        this.$toaster.info("this user is blocked !!");
      }
    },
    //
    blockFriend(friend_id, index, block_type) {
      // or un block
      this.$store
        .dispatch("blockFriend", {
          friend_id: friend_id,
          block_type: block_type
        })
        .then((res) => {
          this.friends[index].isBlock = block_type;

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
    },
    DeleteFriend(friend_id, index) {
      this.$store
        .dispatch("DeleteFriend", { friend_id: friend_id })
        .then((res) => {
          this.friends.pop(this.friends[index]);
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
    }
  }
};
</script>

