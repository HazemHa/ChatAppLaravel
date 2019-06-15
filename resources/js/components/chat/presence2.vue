<template>
   <v-container grid-list-md text-xs-center fluid>



    <v-layout row wrap>


     <v-flex xs6>
        <v-btn class="info" @click="back"> < back </v-btn>
        <v-card ref="scroll" class="scroll" height="550">
          <!--  to distinguish your messages from others -->
        <messages :sender_id="this.$store.getters.user.id" type_chat="room" :recevier="this.$route.params.id"></messages>
              </v-card>

      </v-flex>


       <v-flex xs6>
         <!-- pass current user in room -->
        <currentUser :users="this.$store.getters.currentUserInRoom"></currentUser>
      </v-flex>



    </v-layout>
   </v-container>
</template>
<script>
import currentUser from "./currentUser.vue";
import messagesComponent from "./messages.vue";

export default {
  created() {},
  mounted() {
    this.$store.dispatch(
      "setRoomChannel",
      `message.room.${this.$route.params.id}`
    );
    Echo.join(this.$store.getters.roomChannel)
      .here((users) => {
          console.log(users);
        /**
        here callback will be executed immediately once the channel
         is joined successfully, and will receive an array containing
          the user information for all of the other users currently
           subscribed to the channel
         */
        this.$store.dispatch("setCurrentUserInRoom", users);
      })
      .joining((user) => {
        // joining method will be executed when a new user joins a channe
        this.$toaster.info(user.name + "  join ");
        this.$store.dispatch("addUserToRoom", user);
      })
      .leaving((user) => {
        // leaving method will be executed when a user leaves the channel.
        this.$toaster.info(user.name + "  leave");
        this.$store.dispatch("removeUserFromRoom", user);
      })
      .listen("MessageSent", (e) => {
        // listen to message coming from room
        this.$store.getters.messages.push(e.data);
      });
  },
  updated() {
    var container = this.$el.querySelector(".scroll");
    container.scrollTop = container.scrollHeight;
  },
  components: {
    currentUser: currentUser,
    messages: messagesComponent
  },
  data() {
    return {};
  },
  methods: {
    // leave room and remove current user form list .. to avoid duplicate theme when reenter
    back() {
      Echo.leave(this.$store.getters.roomChannel);
      this.$store.dispatch("removeUserFromRoom", this.$store.getters.user);
      this.$router.go(-1);
    }
  }
};
</script>
<style>
.scroll {
  overflow-y: auto;
}
</style>

